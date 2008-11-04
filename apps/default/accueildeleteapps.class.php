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

class AccueilDeleteApps extends FormModel
{
	function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$miniapps = $currentUser->getPref('miniapps');
		$containers = $currentUser->getPref('containers');
		
		foreach( $_POST as $name => $post )
		{
			if( $name == 'delete_container' )
			{
				$miniapps->deleteApps($post);
			}
			
			if( $name == 'delete_id' )
			{
				$miniapps->deleteApp($post);
				$containers->deleteApp($post);
			}
				
		}
		
		$currentUser->setPref('miniapps', $miniapps );
		$currentUser->setPref('containers', $containers );
		$currentUser->savePrefs($this->db);
	}
}

?>
