<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
ClassLoader::add('Mailer', dirname(__FILE__).'/../mail/classes/mailer.class.php');

class NetCVContactFormSend extends FormModel
{
	public function build()
	{
		if (isset($_POST["hostname"],$_POST["from"],$_POST["subject"],$_POST["message"]) && $_POST["hostname"] != "" && $_POST["from"] != "" && $_POST["subject"] != "" && $_POST["message"] != "")
		{
			$myNetCVGroup = new NetCVGroup($this->db, $_POST["hostname"], TRUE /* readonly */);
			if ($myNetCVGroup->infos != NULL) {
				$myNetCVUser = new NetCVUser($this->db, $myNetCVGroup->getInfo("user_id"));
				$myNetCVSingleCVList = $myNetCVGroup->returnCVListObject();
				if (isset($_POST["lang"]) && $_POST["lang"] != "")
				{
					$myNetCVSingleCV = $myNetCVGroup->CVList->returnCVByLang($_POST["lang"]);
				}
				if ( (!isset($myNetCVSingleCV)) || ($myNetCVSingleCV == FALSE) )
				{
					$myNetCVSingleCV = $myNetCVSingleCVList->returnDefaultCV();
				}
				$myNetCVPersonalInfo = $myNetCVUser->returnPersonalInfo($myNetCVSingleCV->getInfo("id"));
				$email = $myNetCVPersonalInfo["email"];
				
	
				$mail = new Mailer();
				$mail->parseFrom($_POST['from']);
				$mail->parseTo($email);
				$mail->Subject = $_POST['subject'];
				$mail->Body = $_POST['message'];
				
				if( !$mail->Send() )
				{
					Debug::kill($mail);
				}
				else
				{
				}
	
				$this->formMessage->add (FormMessage::SUCCESS, "Le message a été envoyé");
				$this->setRedirectArg('page', 'cv');
				$this->setRedirectArg('hostname', $_POST["hostname"]);
				$this->setRedirectArg('lang', $_POST["lang"]);
				
				//$this->assign("hostname", $_POST["hostname"]);
			}
			else
			{
				Debug::kill("Unknown CV");
			}	
		}
		else
		{
			$this->formMessage->add (FormMessage::FATAL_ERROR, "Tous les champs doivent être remplis");
			$this->setRedirectArg('app', 'netcv');
			$this->setRedirectArg('page', 'cvcontactform');
			$this->setRedirectArg('hostname', $_POST["hostname"]);
			$this->setRedirectArg('lang', $_POST["lang"]);
			
			$_SESSION["from"] = $_POST["from"];
			$_SESSION["subject"] = $_POST["subject"];
			$_SESSION["message"] = $_POST["message"];
			
		}

		$this->formMessage->setSession();
	}
}

?>
