<?php 

/**
 * @version $Id: minichatgrand.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MCDefault extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("config", $config);
		if ($this->currentUser->isLogged())
		{
			/* POST */
			if (isset($_POST['post']))
			{
				$message = $_POST['post'];
				if (get_magic_quotes_gpc()) {
					$message = stripslashes($message);
				}
			}
			$flooding = false;
			$flood_sql = "SELECT COUNT(*) FROM minichat WHERE id_auteur=:userId AND TIME_TO_SEC(TIMEDIFF(NOW(), `time`)) < 60";
			try {
				$stmt = $this->db->prepare($flood_sql);
				$stmt->bindValue(":userId", $this->currentUser->getID());
				$stmt->execute();
				$row = $stmt->fetch();
				if ($row[0] >= 20)
					$flooding = true;
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}
			if ((isset($message)) && (strlen(trim($message)) > 0) && !$flooding)
			{
				$req_sql = "INSERT INTO minichat 
					(time, id_auteur, post) VALUES
					(NOW(), :userId, :message)";
				try
				{
					$stmt = $this->db->prepare($req_sql);
					$stmt->bindValue(":userId", $this->currentUser->getID());
					$stmt->bindValue(":message", htmlspecialchars($message));
					$stmt->execute();
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
			}
		}
		if( isset($this->args['maxlines']) && $this->args['maxlines'] != "" )
		{
			$max = $this->args['maxlines'] ;
		}
		else
		{
			$max = $config["max"]["small"] ;
		}

		if( isset($this->args['userichtext']) && $this->args['userichtext'] != "" )
		{
			$userichtext = $this->args['userichtext'];
		}
		else
		{
			$userichtext = $config["userichtext"]["small"];
		}
		if( isset($this->args['inversepostorder']) && $this->args['inversepostorder'] != "" )
		{
			$inversepostorder = $this->args['inversepostorder'];
		}
		else
		{
			$inversepostorder = $config["inversepostorder"]["small"];
		}
		
		$emoticons = new Emoticons($this->userFactory);
		
		if( isset($this->args['emoticon_theme']) && $this->args['emoticon_theme'] != "" )
		{
			$emoticon_theme = $this->args['emoticon_theme'];
			$emoticons->set_user_emoticon_theme($this->args['emoticon_theme']);
		}
		else
		{
			$emoticon_theme = $config["emoticon_theme"]["small"];
			//$emoticons->set_user_emoticon_theme($config["emoticon_theme"]["small"]);
		}
		$this->assign("emoticon_theme", $emoticon_theme);
		$this->assign("maxlines", $max);
		$this->assign("userichtext", $userichtext);
		$this->assign("inversepostorder", $inversepostorder);
		$userichtext = ($userichtext == 1);
		$inversepostorder = ($inversepostorder == 1);
		
		if(isset($this->args['pagenum']) && $this->args['pagenum'] != "")
			$page = $this->args['pagenum'];
		else
			$page = 1;
			
		$this->assign("pagenum", $page);
		$minichatMessageList = new MinichatMessageList($this->db, $this->userFactory, $userichtext, $inversepostorder);
		$dateRange = $minichatMessageList->dateRange();
		$this->assign("minDate", $dateRange[0]);
		$this->assign("maxDate", $dateRange[1]);
		$page_count = ceil($minichatMessageList->count() / $max);
		
		if ($page_count > 1)
		{
			$pages = range(1, $page_count);
			$this->assign('pages', $pages);
			$this->assign('page', $page);
		}
		if ((isset($this->args["day"])) && ($this->args["day"] != "")) {
            $this->assign("post", $minichatMessageList->getMessagesFromDate($this->args["day"]));
        } else {
            if (isset($max) && isset($page))
                $this->assign("post", $minichatMessageList->getMessages($max, $page));
            else
                $this->assign("post", $minichatMessageList->getMessagesFromDate(time()));
        }

		$this->assign('permission', $this->permission);
		$this->assign('time', time());
	}
}

?>
