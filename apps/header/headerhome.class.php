<?php
/**
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
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
//		$url = BaseUrl::getRef();
//		print_r($url->getApp());
		
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
		
		$this->assign('base_url', $GLOBALS['config']['base_url']);
		$this->assign("styles", $GLOBALS['config']['style']);
	}
	

}

?>
