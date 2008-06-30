<?php
/**
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class HeaderHome extends Model
{
	function build()
	{
		$this->assign("basehref", $GLOBALS["config"]['site']['base_url']);
		if( $theme = $this->currentUser->getPref('theme') )
		{
			$this->assign("cssFile", $GLOBALS['config']['style']
				[$theme]
				['home_css']);
		}
		else
		{
			$this->assign("cssFile", $GLOBALS['config']['style']
				[0]
				['home_css']);
		}
		
		$this->assign("styles", $GLOBALS['config']['style']);
		
		if( $this->currentUser->isLogged() )
		{
			$this-> assign("currentUserName", $this->currentUser->getFullName() );
		}
	}
	

}

?>
