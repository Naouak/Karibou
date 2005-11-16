<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

ClassLoader::add('Mailer', dirname(__FILE__).'/classes/mailer.class.php');

class MailSend extends FormModel
{
	function build()
	{
		if( isset($_POST['from'], $_POST['to'], $_POST['body']) )
		{
			$mail = new Mailer();
			$mail->parseFrom($_POST['from']);
			$mail->parseTo($_POST['to']);
			$mail->parseCC($_POST['cc']);
			$mail->Subject = stripslashes($_POST['subject']);
			$mail->Body = stripslashes($_POST['body']);
			
			if( !$mail->Send() )
			{
				Debug::kill($mail);
			}
		}
	}
}
 
?>