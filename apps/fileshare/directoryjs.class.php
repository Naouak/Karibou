<?php
/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@pinaraf.info>
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
class FileShareDirectoryJS extends Model
{
	public function build()
	{
		$folderName = filter_input(INPUT_POST, "folder");

		if (isset($folderName) && ($folderName != ""))
		{
			$path = '';
			foreach (split("/", $folderName) as $directory )
			{
				if ($directory != '')
				{
					$path .= $directory."/";
				}
			}

			$myDir = new KDirectory($this->db, $this->userFactory, $this->permission, $path);
		}
		else
		{
			$myDir = new KDirectory($this->db, $this->userFactory, $this->permission);
		}

		$this->assign("myDir",$myDir);
		$this->assign("viewtype", "detailed");
		$foldersOutput = array();
		foreach ($myDir->returnSubDirList() as $dir) {
			$foldersOutput[] = array("name" => $dir->getName(), "date" => $dir->getModificationDate(), "path" => $dir->getPath());
		}
		$filesOutput = array();
		foreach ($myDir->returnFileList() as $file) {
			//$params = array("page" => "download", "filename" => $file->getPathBase64(), "app" => "fileshare");
			//$filesOutput[] = array("name" => $file->getName(), "date" => $file->getModificationDate(), "path" => $file->getPath(), "download" => kurl($params, $this->appList));
			$filesOutput[] = array("name" => $file->getName(), "date" => $file->getModificationDate(), "path" => $file->getPath(), "download" => $file->getPathBase64(), "extension" => $file->getExtension());
		}
		$this->assign("jsonOutput", json_encode(array("folders" => $foldersOutput, "files" => $filesOutput)));
	}
}

?>
