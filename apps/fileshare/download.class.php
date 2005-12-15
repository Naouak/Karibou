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
class FileShareDownload extends Model
{
	public function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		
		if (isset($this->args["filename"]) && $this->args["filename"] != "")
		{
			$file = new KFile ($this->db, $this->userFactory, base64_decode($this->args["filename"]));
			
			//$filepath = KARIBOU_PUB_DIR.'/fileshare/'.base64_decode($this->args["filename"]);
	
			if (isset($file))
			{
				if (is_file($file->getFullPath()))
				{
					//$handle = fopen ($file->getFullPath(), "r");
					header("Content-disposition: attachment; filename=\"".$file->getName()."\"");
					header("Content-Type: application/force-download");
					header("Content-Transfer-Encoding: binary");
					header("Content-Length: ".$file->getSize());
					header("Pragma: no-cache");
					header("Expires: 0");
					//echo fread ($handle, $file->getSize());
					//fclose ($handle);
					readfile($file->getFullPath());
					exit;
				
				}
			}
		}
		elseif (isset($this->args["fileid"],$this->args["versionid"]) && $this->args["fileid"] != "" && $this->args["versionid"] != "")
		{
			$file = new KFile ($this->db, $this->userFactory, FALSE, FALSE, $this->args["fileid"]);
			$versionFilePath = KARIBOU_PUB_DIR.'/fileshare/versions/'.$this->args["fileid"].".".$this->args["versionid"];
			if (is_file($versionFilePath))
			{
					header("Content-disposition: attachment; filename=\"".$file->getName()."\"");
					header("Content-Type: application/force-download");
					header("Content-Transfer-Encoding: binary");
					header("Content-Length: ".$file->getSize());
					header("Pragma: no-cache");
					header("Expires: 0");
					readfile($versionFilePath);
					exit;
			}
		}
		

	}
}

?>