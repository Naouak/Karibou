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
class FileShareSaveDirectory extends FormModel
{
	protected $text;

	public function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();

		if (isset($_POST['directoryname']) && $_POST['directoryname'] != '')
		{
			$dir = new KDirectory($this->db, base64_decode($_POST['directoryname']));
		}
		else
		{
			$dir = new KDirectory($this->db);
		}

		if( isset($_POST["newdirectoryname"]) && ($this->permission > _READ_ONLY_) )
		{
			$this->text = new KText();
			$directorytxt = $this->text->epureString($_POST["newdirectoryname"]);
			
			$dir->createSubDirectory($directorytxt);

			if (isset($_POST["owner"]) && $_POST["owner"] != "")
			{
				$owner = $_POST["owner"];
			}
			else
			{
				$owner = NULL;
			}
								
			$kdbfsw = new KDBFSElementWriter ($this->db);
			$kdbfsw->writeInfos(
					array(
						"name"		=> $directorytxt,
						"parent"		=> $dir->getFolderId(),
						"creator"		=> $this->currentUser->getId(),
						"groupowner"	=> $owner,
						"type"		=> 'folder'),
					
						array(
/*	
						"group"		=> $owner,
						"rights"		=> 7
*/
						),

					array (
					/*
						"description"	=> $_POST["description"],
						"user"		=> $this->currentUser->getId()
					*/
					)
				);

			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'directory');
			$this->setRedirectArg('directoryname', $dir->getPathBase64());
		}
		else
		{
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'createdirectory');
			$this->setRedirectArg('directoryname', $dir->getPathBase64());
		}
	}
	
}

?>