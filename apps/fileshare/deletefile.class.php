<?php 
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class FileShareDeleteFile extends FormModel
{

	public function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
			if (isset($_POST["fileid"]))
			{
				//Check rights
				
				$myFile = new KFile ($this->db, $this->userFactory, FALSE,FALSE, $_POST["fileid"]);

				//Get last version id + 1
				$id = $myFile->getLastVersionInfo("id");

				$actualVersionId = $myFile->getLastVersionInfo("versionid");
				
				if ($myFile->canWrite() && isset($actualVersionId, $id) && ($actualVersionId !== FALSE) && ($actualVersionId != "") && ($id !== FALSE) && ($id != ""))
				{
					$versionFilePath = KARIBOU_PUB_DIR.'/fileshare/versions/'.$id.".".$actualVersionId;
							//Backup  / Move actual file in fvers/id.versionid
							$actualLocation = $myFile->getFullPath();

							if (copy($actualLocation, $versionFilePath))
							{
								//Copy uploaded file at backuped file original location
								//Update db
                                unlink($actualLocation);
								$kdbfsw = new KDBFSElementWriter ($this->db, $id);
								$kdbfsw->setAsDeleted();
							}
							else
							{
								Debug::Kill ("Backup failed");
							}
							$this->setRedirectArg('app', 'fileshare');
							$this->setRedirectArg('page', 'directory');
							$this->setRedirectArg('directoryname', $myFile->getParentPathBase64());
                }
        }
		else
		{
			$this->setRedirectArg('app', 'fileshare');
		}
	}
	
}

?>