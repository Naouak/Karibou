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
	{		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory, $this->permission);
		
		if (isset($_POST["elementid"], $_POST["destinationid"]) && $_POST["elementid"] != "")
		{
			$myFile = $myKDBFSElementFactory->move($_POST["elementid"], $_POST["destinationid"]);
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'details');
			$this->setRedirectArg('elementpath', $myFile->getPathBase64());
		}
		else
		{
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'movewhere');
			$this->setRedirectArg('elementpath', $_POST["elementid"]);
		}
	}
	
}

?>