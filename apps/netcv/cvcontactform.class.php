<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 

class NetCVContactForm extends Model
{
	public function build()
	{
		if (isset($this->args["hostname"]) && $this->args["hostname"] != "")
		{
			if (isset($this->args["lang"])) {
				$this->assign("lang", $this->args["lang"]);
			}
			$this->assign("hostname", $this->args["hostname"]);	
			
			//Verification de la presence d'erreur et affection du message d'erreur a afficher
			$this->assign("netcvMessages", $this->formMessage->getSession());
			$this->formMessage->flush();
			
			if (isset($_SESSION["from"],$_SESSION["subject"],$_SESSION["message"]))	{
				$this->assign("from", $_SESSION["from"]);
				$this->assign("subject", $_SESSION["subject"]);
				$this->assign("message", $_SESSION["message"]);
				unset($_SESSION["from"],$_SESSION["subject"],$_SESSION["message"]);
			}
		}
		else
		{
			//Penser à rediriger vers une page plus sympatique, 404 ou truc du genre
			Debug::kill("Unknown CV");
		}
		
	}
}

?>