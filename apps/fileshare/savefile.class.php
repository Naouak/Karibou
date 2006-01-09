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
			if (isset($_POST["fileid"]))
			{
				//Check rights
				
				$myFile = new KFile ($this->db, $this->userFactory, $this->permission, FALSE,FALSE, $_POST["fileid"]);

				//Get last version id + 1
				$id = $myFile->getLastVersionInfo("id");

				$actualVersionId = $myFile->getLastVersionInfo("versionid");
				$newVersionId = $actualVersionId + 1;
				
				if (isset($newVersionId, $id) && ($actualVersionId !== FALSE) && ($actualVersionId != "") && ($id !== FALSE) && ($id != "") )
				{
					$versionFilePath = KARIBOU_PUB_DIR.'/fileshare/versions/'.$id.".".$actualVersionId;
					
					//Overwrite if versioned file exists
					/*
					while (is_file($versionFilePath) || is_dir($versionFilePath))
					{
						$versionFilePath = KARIBOU_PUB_DIR.'/fileshare/versions/'.$id.".".++$newVersionId;
					}
					*/
					
					//if (!is_file($versionFilePath) && !is_dir($versionFilePath))
					//{
						
						if (isset($_FILES))
						{
							
							reset($_FILES);
							$file = current($_FILES);
							
							//Backup  / Move actual file in fvers/id.versionid
							$actualLocation = $myFile->getFullPath();

							if (copy($actualLocation, $versionFilePath))
							{
								
								//Copy uploaded file at backuped file original location
	
								//Save file in fileshare_versions/id.versionid
								move_uploaded_file($file['tmp_name'], $actualLocation);

								//Update db
								$kdbfsw = new KDBFSElementWriter ($this->db, $id);
										$kdbfsw->writeInfos(
												array(),
												array(),
		
												array (
													"description"	=> $_POST["description"],
													"uploadname"	=> $file['name'],
													"versionid"		=> $newVersionId,
													"user"		=> $this->currentUser->getId())
											);
								
								$this->setRedirectArg('app', 'fileshare');
								$this->setRedirectArg('page', 'details');
								$this->setRedirectArg('elementpath', $myFile->getPathBase64());
								//$this->setRedirectArg('page', 'directory');
								//$this->setRedirectArg('directoryname', $dir->getPathBase64());
							}
							else
							{
								Debug::Kill ("Backup failed");
							}
						}
						//CASE OF DELETION???
						else
						{
							Debug::kill("No post file");
						}
/*
					}
					else
					{
						Debug::Kill("FS element exists!");
					}
*/
				}
				else
				{
					Debug::kill("DBFS return issue");
				}
			}
			else
			{
		
				if (isset($_POST['directoryname']) && $_POST['directoryname'] != '')
				{
					$dir = new KDirectory($this->db, $this->userFactory, $this->permission, base64_decode($_POST['directoryname']));
				}
				else
				{
					$dir = new KDirectory($this->db, $this->userFactory, $this->permission);
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
						 
							$kfile = new KFile($this->db, $this->userFactory, $this->permission, $file['name']);
							
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
									
									$kdbfsw = new KDBFSElementWriter ($this->db);
									
									$kdbfsw->writeInfos(
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
												"versionid"	=> 1,
												"uploadname"	=> $file['name'],
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
		}
		else
		{
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'upload');
		}
	}
	
}

?>