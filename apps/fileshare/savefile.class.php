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
class FileShareSaveFile extends FormModel
{
protected $text;

	public function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();

		if( isset($_FILES) && ($this->permission > _READ_ONLY_) )
		{
		
			if (isset($_POST['directoryname']) && $_POST['directoryname'] != '')
			{
				$dir = new KDirectory($this->db, base64_decode($_POST['directoryname']));
			}
			else
			{
				$dir = new KDirectory($this->db);
			}
		
			$this->text = new KText();

			foreach( $_FILES as $key => $file)
			{
				if (!is_dir(KARIBOU_PUB_DIR.'/fileshare'))
				{
					mkdir(KARIBOU_PUB_DIR.'/fileshare', 0700);
				}

				if( !empty($file['name']) )
				{
					if ( ($file["size"] == 0) || ($file["size"] < ini_get("upload_max_filesize")) )
					{
								//Size issue
								$this->setRedirectArg('app', 'fileshare');
								$this->setRedirectArg('page', 'upload');
					}
					else
					{
					 
						$kfile = new KFile(FALSE, $file['name']);
						
						//$filename = $this->text->epureString($file['name']);
						$shortfilename = $this->text->epureString($kfile->getShortName());
						$fileextension = $this->text->epureString($kfile->getExtension());
						if ($fileextension != '')
						{
							$fileextension = '.'.$fileextension;
						}
					
						$append = '';
						$locationfree = FALSE;
						
						while (!$locationfree)
						{
							if (!is_file($dir->getFullPath()."/".$shortfilename.$append.$fileextension)) {
								move_uploaded_file($file['tmp_name'], $dir->getFullPath()."/".$shortfilename.$append.$fileextension);
								unset($file['tmp_name']);
								$args[$key] = $file;
								$locationfree = TRUE;
								
								if (isset($_POST["owner"]) && $_POST["owner"] != "")
								{
									$owner = $_POST["owner"];
								}
								else
								{
									$owner = NULL;
								}
								
								$kdbfsw = new KDBFSElementWriter 
									(	
										$this->db, 
										array(
											"name"		=> $shortfilename.$append.$fileextension,
											"parent"		=> $dir->getFolderId(),
											"creator"		=> $this->currentUser->getId(),
											"groupowner"	=> $owner,
											"type"		=> 'file'),
										array(

/*										
											"group"		=> NULL,
											"rights"		=> 7
*/
										),

										array (
											"description"	=> $_POST["description"],
											"user"		=> $this->currentUser->getId())
									);
							}
							else
							{
								if($append == '')
								{
									$append = 1;
								}
								else
								{
									$append++;
								}
							}
						}
						$this->setRedirectArg('app', 'fileshare');
						$this->setRedirectArg('page', 'directory');
						$this->setRedirectArg('directoryname', $dir->getPathBase64());
					}
				}
				else
				{
					//Size issue
					$this->setRedirectArg('app', 'fileshare');
					$this->setRedirectArg('page', 'upload');
					$this->setRedirectArg('directoryname', $dir->getPathBase64());
				}
			}
		}
		else
		{
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'upload');
		}
	}
	
}

?>