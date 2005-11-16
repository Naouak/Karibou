<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Contact extends Model
{

	public function build()
	{	
		$this->assign('permission', $this->permission);
		
		if (isset($_SESSION["contactMessageSent"]))
		{
			$this->assign('sent', $_SESSION["contactMessageSent"]);
			unset($_SESSION["contactMessageSent"]);
		}
	}
}

?>