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
class FileShareMove extends FormModel
{
	public function build()
	{		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory);
		
		if (isset($_POST["elementid"], $_POST["destinationid"]) && $_POST["elementid"] != "")
		{
			$myFile = $myKDBFSElementFactory->move($_POST["elementid"], $_POST["destinationid"]);
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'filedetails');
			$this->setRedirectArg('filename', $myFile->getPathBase64());
		}
		else
		{
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'movewhere');
			$this->setRedirectArg('filename', $_POST["elementid"]);
		}
	}
	
}

?>