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
			$max = 5 ;
		}
		$this->assign("maxlines", $max);
		
		$req_sql = 'SELECT COUNT(*) as nb FROM minichat';
		try
		{
			$stmt = $this->db->query($req_sql);
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$count = $row["nb"];
		unset($stmt);
		unset($row);
		
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
	
		$req_sql = 'SELECT UNIX_TIMESTAMP(time) as timestamp, id_auteur, post 
					FROM minichat ORDER BY time DESC LIMIT '.$max.' OFFSET '.(($page-1)*$max);

		$post = array ();
		try
		{
			$stmt = $this->db->query($req_sql);
		}
		catch( PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$line = new MinichatPost(
				$row['timestamp'], 
				$this->userFactory->prepareUserFromId($row['id_auteur']), 
				$row['post']);
			$post[] = $line;
		}
		$post = array_reverse($post);
		$this->assign("post", $post);

		$this->assign('permission', $this->permission);
	}
}

class MinichatPost
{
	protected $time;
	protected $auteur;
	protected $txt_post;

	function __construct($time, $auteur, $txt_post)
	{
		$this->time = $time;
		$this->auteur = $auteur;
		$this->txt_post = $txt_post;
	}

	function getAuthor()
	{
		return $this->auteur->getUserLink();
	}
	function getAuthorLogin()
	{
		return $this->auteur->getlogin();
	}

	function getDate()
	{
		return date('H:i', $this->time);
	}

	function getPost()
	{
		return $this->txt_post;
	}
}

?>
