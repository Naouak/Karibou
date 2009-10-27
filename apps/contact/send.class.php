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
		$from = filter_input(INPUT_POST, "from", FILTER_VALIDATE_EMAIL);
		$subject = filter_input(INPUT_POST, "subject", FILTER_SANITIZE_STRING);
		$message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_STRING);
		if( ($from !== false) && ($subject !== false) && ($message !== false) )
		{
			$app = $this->appList->getApp($this->appname);
			$config = $app->getConfig();
		
			$mail = new PHPMailer();
			$mail->CharSet = "UTF-8";
			$mail->From = $from;
			$mail->FromName = $from;
			$mail->addAddress($GLOBALS['config']['contactemail']);
			$mail->Subject = $subject;
			$mail->Body = "Karibou speaking :\r\n" . $message;
			
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
