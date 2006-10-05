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
class FileShareRename extends FormModel
{
	public function build()
	{

		if (isset($_POST["elementid"], $_POST["name"], $_POST["description"]) && $_POST["name"] != "" && $_POST["description"] != "")
		{
			$myElement = new KDBFSElement($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $_POST['elementid']);
			
			if ($myElement->canWrite())
			{
				$myNewElement = $myKDBFSElementFactory->rename($_POST["elementid"], $_POST["name"], $_POST["description"]);

				if (is_object($myNewElement) && $myNewElement !== FALSE)
				{
					$this->setRedirectArg('app', 'fileshare');
					$this->setRedirectArg('page', 'details');
					$this->setRedirectArg('elementpath', $myNewElement->getPathBase64());
				}
				else
				{
					$this->setRedirectArg('app', 'fileshare');
					$this->setRedirectArg('page', 'renameform');
					$this->setRedirectArg('elementid', $_POST["elementid"]);
				}
			}
		}
		else
		{
			$this->setRedirectArg('app', 'fileshare');
			$this->setRedirectArg('page', 'renameform');
			$this->setRedirectArg('elementid', $_POST["elementid"]);
		}
	}
	
}

?>