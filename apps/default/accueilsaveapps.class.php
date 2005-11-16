<?php

/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/


/**
 * 
 * @package applications
 */

class AccueilSaveApps extends FormModel
{
	function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$containers = $currentUser->getPref('containers');
		
		foreach($containers as $key => $c)
		{
			$containers->setApps($key, array());
			$containers->setDefaultApps(array());
		}

		foreach( $_POST as $name => $post )
		{
			if( preg_match('/^app_container[0-9]+$/', $name) )
			{
				if( isset($containers[$name]) )
				{
					$containers->setApps($name, $post);
				}
			}
			if( preg_match('/^default_container$/', $name) )
			{
				$containers->setDefaultApps($post);
			}
		}
		
		$currentUser->setPref('containers', $containers );
	}
}

?>
