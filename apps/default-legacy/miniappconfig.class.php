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

class MiniAppConfig extends FormModel
{
	function build()
	{
		if( isset($_POST['miniappid']) )
		{
			$currentUser = $this->userFactory->getCurrentUser();
			$miniapps = $currentUser->getPref('miniapps') ;
			
			$args = $_POST;
			
			if( isset($_FILES) )
			{
				foreach( $_FILES as $key => $file)
				{
					if( !empty($file['name']) )
					{
						move_uploaded_file($file['tmp_name'], KARIBOU_PUB_DIR.'/'.$file['name']);
						unset($file['tmp_name']);
						$args[$key] = $file ;
					}
				}
			}
			
			$miniapps->setArgs( $_POST['miniappid'], $args );
			
			$currentUser->setPref('miniapps', $miniapps );
		}
	}
}

?>