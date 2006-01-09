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
				$element = new KDBFSElement($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $_POST["fileid"]);
				
				if ($element->getType() == "file")
				{
					$myElement = new KFile ($this->db, $this->userFactory, $this->permission, FALSE,FALSE, $_POST["fileid"]);
				}
				else
				{
					$myElement = new KDirectory ($this->db, $this->userFactory, $this->permission, FALSE,FALSE, $_POST["fileid"]);					
				}
				


				if ($myElement->isFile())
				{
					//Check rights
		
					//Get last version id + 1
					$id = $myElement->getLastVersionInfo("id");
		
					$actualVersionId = $myElement->getLastVersionInfo("versionid");
					
					if ($myElement->canWrite() && isset($actualVersionId, $id) && ($actualVersionId !== FALSE) && ($actualVersionId != "") && ($id !== FALSE) && ($id != ""))
					{
						$versionFilePath = KARIBOU_PUB_DIR.'/fileshare/versions/'.$id.".".$actualVersionId;
						//Backup  / Move actual file in fvers/id.versionid
						$actualLocation = $myElement->getFullPath();

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
						$this->setRedirectArg('directoryname', $myElement->getParentPathBase64());
					}
				}
				elseif ($myElement->isDirectory())
				{
					if ($myElement->isEmpty())
					{
						if ($myElement->canWrite())
						{
							$kdbfsw = new KDBFSElementWriter ($this->db, $myElement->getElementId());
							$kdbfsw->setAsDeleted();
							
							rmdir($myElement->getFullPath());
						}
						else
						{
						}
					}
					else
					{
					}
					$this->setRedirectArg('app', 'fileshare');
					$this->setRedirectArg('page', 'directory');
					$this->setRedirectArg('directoryname', $myElement->getParentPathBase64());
				}
	   }
		else
		{
			$this->setRedirectArg('app', 'fileshare');
		}
	}
	
}

?>