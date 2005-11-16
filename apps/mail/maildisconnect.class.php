<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MailDisconnect extends FormModel
{
	function build()
	{
		if( isset($_SESSION['mail_connection']) )
		{
			session_unregister('mail_connection');
		}
	}
}
 
?>
