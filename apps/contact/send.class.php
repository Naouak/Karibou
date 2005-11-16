<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

require_once KARIBOU_LIB_DIR.'/phpmailer/class.phpmailer.php';

class Send extends FormModel
{
	function build()
	{
		if( isset($_POST['from'], $_POST['subject'], $_POST['message']) )
		{
			$app = $this->appList->getApp($this->appname);
			$config = $app->getConfig();
		
			$mail = new PHPMailer();
			$mail->CharSet = "UTF-8";
			$mail->From = $_POST['from'];
			$mail->to = array($config["email"]);
			$mail->Subject = stripslashes($_POST['subject']);
			$mail->Body = stripslashes($_POST['message']);
			
			if( !$mail->Send() )
			{
				$_SESSION["contactMessageSent"] = FALSE;
				Debug::kill($mail);
			}
			else
			{
				$_SESSION["contactMessageSent"] = TRUE;
			}

			$this->setRedirectArg('page', '');
		}
	}
}
 
?>
