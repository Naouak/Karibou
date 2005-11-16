<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
 
$GLOBALS['myTranslation'] = new NetCVTempTranslation();

class NetCVItemSave extends FormModel
{
	public function build()
	{
    	if(isset($_POST["netcvTitleItem"], $_POST["netcvSingleCVId"], $_POST["netcvSubtitleItem"],$_POST["netcvDescriptionItem"])) {

			$myNetCVUser = new NetCVUser($this->db, $this->currentUser, TRUE);			$myNetCVUser->getCVContent($_POST["netcvSingleCVId"]);
			$myNetCV = $myNetCVUser->returnCVContent($_POST["netcvSingleCVId"]);

            	$descriptionProcessed = $_POST["netcvDescriptionItem"];

            	if(isset($_POST["pid"]) && $_POST["pid"] != "" ) {
            		   if (isset($_POST["netcvTitleItemId"]) && $_POST["netcvTitleItemId"] != "") {
            			   //Update
								$myNetCV->updateElementInfos ($_POST["netcvTitleItemId"],$_POST["netcvTitleItem"]);
								$titleID = $_POST["netcvTitleItemId"];
								$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("ELEMENT_UPDATED"));
            			} else {
            			   //Insert & link !
       				       $titleID = $myNetCV->insertElementLevel($_POST["pid"],$_POST["netcvTitleItem"]);
								$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("ELEMENT_ADDED"));
            			}

            		   if (isset($_POST["netcvSubtitleItemId"]) && $_POST["netcvSubtitleItemId"] != "") {
            			   //Update
								$myNetCV->updateElementInfos ($_POST["netcvSubtitleItemId"],$_POST["netcvSubtitleItem"]);
								$subTitleID = $_POST["netcvSubtitleItemId"];
            			} else {
            			   //Insert & link !
            				 $subTitleID = $myNetCV->insertElementLevel($titleID,$_POST["netcvSubtitleItem"]);
            			}

            		   if (isset($_POST["netcvDescriptionItemId"]) && $_POST["netcvDescriptionItemId"] != "") {
            			   //Update
								$myNetCV->updateElementInfos ($_POST["netcvDescriptionItemId"],$_POST["netcvDescriptionItem"]);
            			} else {
            				$myNetCV->insertElementLevel($subTitleID,$descriptionProcessed);
            			   //Insert & link !
            			}

							$myNetCVUser->updateResumeCompletion();

              }
        }
		$this->formMessage->setSession();
	}
}
?>
