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
			}
			if (isset($message))
			{
				$req_sql = "INSERT INTO minichat 
					(time, id_auteur, post) VALUES
					(NOW(), :userId, :message)";
				try
				{
                    $stmt = $this->db->prepare($req_sql);
                    $stmt->bindValue(":userId", $this->currentUser->getID());
                    $stmt->bindValue(":message", stripslashes($message));
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
		$this->assign("maxlines", $max);
		
		
		if(isset($this->args['pagenum']) && $this->args['pagenum'] != "")
			$page = $this->args['pagenum'];
		else
			$page = 1;
			
		$this->assign("pagenum", $page);

		$minichatMessageList = new MinichatMessageList($this->db, $this->userFactory);
        $min_date = $minichatMessageList->minDate();
        $max_date = $minichatMessageList->maxDate();
        $this->assign("minDate", $min_date);
        $this->assign("maxDate", $max_date);
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
            $this->assign("post", $minichatMessageList->getMessages($max, $page));
        }

		$this->assign('permission', $this->permission);
	}
}

?>