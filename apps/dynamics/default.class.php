<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 *
 * @package applications
 **/
class DynamicsDefault extends Model
{
	public function build()
	{
		$tab = array();
		$tab['dynamics'] = array();
	
		/**
		 * Utilisateurs en ligne : Accès à la base de données pour récupération des utilisateurs en ligne
		 */
		$app = $this->appList->getApp('onlineusers');
		$config = $app->getConfig();
		$maxage = $config["max"]["age"];

		//Lister tous les utilisateurs en ligne dans un tableau
		$sql = "SELECT * FROM onlineusers WHERE timestamp > '".(time()-$maxage)."' ";
		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
        $onlineusers = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
        foreach($onlineusers as &$myuser)
        {
            $myuser["object"] = $this->userFactory->prepareUserFromId($myuser['user_id']);
        }
		
		/**
		 * Mini-Chat : Accès à la base de données pour récupération des messages
		 */
		$minichatMessageList = new MinichatMessageList($this->db, $this->userFactory);		
		$minichatmessages = $minichatMessageList->getMessages(8,1);
		
		/**
		 * UserFactory : Récupération des utilisateurs
		 */
        $this->userFactory->setUserList();
		
		/**
		 * Assignation des variables
		 */
		//Utilisateurs en ligne
		$tab['dynamics']['onlineusers'] = array();
		foreach($onlineusers as $user)
		{
			$tab['dynamics']['onlineusers'][] = array('id' => $user['user_id'], 'displayname' => $user['object']->getDisplayName(), 'username' => $user['object']->getLogin(), 'picturepath' => $user['object']->getPicturePath());
		}
		
		//MiniChat
		$tab['dynamics']['minichat'] = array();
		foreach($minichatmessages as $message)
		{
			$tab['dynamics']['minichat'][] = array('id' => $message->getAuthorObject()->getId(), 'displayname' => $message->getAuthorObject()->getDisplayName(), 'username' => $message->getAuthorObject()->getLogin(), 'picturepath' => $user['object']->getPicturePath(), 'message' => $message->getPost());
		}

		/*
		if ($this->currentUser->isLogged())
		{
			//POST
			if (isset($_POST['post']))
			{
				$message = $_POST['post'];
			}
			if (isset($message))
			{
				$req_sql = "INSERT INTO minichat 
					(time, id_auteur, post) VALUES
					(NOW(), " .	$this->currentUser->getID() . ", '" .$message . "')";
				try
				{
					$this->db->exec($req_sql);
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
			}
		}
		*/

		/**
		 * Variables courantes
		 */
		//Définition de l'utilisation des flashmails
		if (isset($GLOBALS['config']['noflashmail']))
			$this->assign("noflashmail", $GLOBALS['config']['noflashmail']);
		else
			$this->assign("noflashmail", FALSE);
		
		$this->assign("islogged", $this->currentUser->isLogged());
		
		/**
		 * Affichage : /!\ non respect du modèle MVC pour performance -> comment assurer une connexion persistante sans afficher dans le modèle ou sans faire de requêtes sur la base dans le template
		 */
		header('Content-Type: text/plain');

		//$tab['onlineusers'] = array_merge($tab['onlineusers'],$tab['onlineusers']);
		//$tab['onlineusers'] = array_merge($tab['onlineusers'],$tab['onlineusers']);
		//$tab['onlineusers'] = array_merge($tab['onlineusers'],$tab['onlineusers']);
		
		/**
		 * Sérialisation de la variable $tab et affichage
		 */
		echo htmlspecialchars(serialize(removeQuotesInArray($tab)));
		 
	}
}

/**
 * Remplacement des doubles quotes dans le tableau
 */
function removeQuotesInArray($array)
{
	if (is_array($array))
	{
		foreach($array as &$element)
		{
			if (is_array($element))
			{
				$element = removeQuotesInArray($element);
			}
			else
			{
				$element = str_replace('"', '\'', $element);
			}
		}
	}
	else
	{
		$array = str_replace('"', '\'', $array);
	}
	return $array;
}

?>