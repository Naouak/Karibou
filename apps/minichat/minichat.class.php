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

class MiniChat extends Model
{
	public function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("config", $config);
		
		/* POST */
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
	
	
		if( isset($this->args['maxlines']) && $this->args['maxlines'] != "" )
		{
			$max = $this->args['maxlines'] ;
		}
		else
		{
			$max = $config["max"]["small"] ;
		}
		$this->assign("maxlines", $max);
		
		$minichatPostList = new MinichatPostList($this->db, $this->userFactory);
		
		
		$count = $minichatPostList->count();
		
		if(isset($this->args['pagenum']) && $this->args['pagenum'] != "")
			$page = $this->args['pagenum'];
		else
			$page = 1;
		$this->assign("pagenum", $page);
		
		$page_count = ceil($count / $max);
		if($page_count > 1)
		{
			$pages = range(1, $page_count);
			$this->assign('pages', $pages);
			$this->assign('page', $page);
		}
		
		$this->assign("post", $minichatPostList->getMessages($max, $page));

		$this->assign('permission', $this->permission);
	}
}

?>