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
	
		/**
		 * Utilisateurs en ligne
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
		
        foreach($onlineusers as &$user)
        {
            $user["object"] = $this->userFactory->prepareUserFromId($user["user_id"]);
        }
        $this->userFactory->setUserList();
		$this->assign("onlineusers", $onlineusers);
		
		$tab['onlineusers'] = array();
		foreach($this->vars['onlineusers'] as $user)
		{
			$tab['onlineusers'][] = array('id' => $user['user_id'], 'displayname' => $user['object']->getDisplayName(), 'username' => $user['object']->getLogin(), 'picturepath' => $user['object']->getPicturePath());
		}
		
		/**
		 * Mini-Chat
		 */
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
		$minichatMessageList = new MinichatMessageList($this->db, $this->userFactory);		
		$minichatmessages = $minichatMessageList->getMessages(8,1);
		//$this->assign("post", $minichatMessageList->getMessages($max, $page));
		foreach($minichatmessages as $message)
		{
			//$tab['minichat'][] = array('userid' => $message->getAuthorObject()->getId(), 'username' => $message->getAuthorObject()->getLogin(), 'displayname' => $message->getAuthorObject()->getDisplayName(), 'message' => $message->getPostXHTML());
		}
		$tab['minichat'] = array("etest");
		//echo '<pre>';
		//var_dump($tab);
	
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