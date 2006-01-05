<?php 

/**
 * @copyright 2006 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MiniChatMessageList
{
	protected $db;
	protected $wiki;

	function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
	
		/* Wiki Construct */
		$this->wiki = new wiki2xhtmlBasic();
		$this->wiki->wiki2xhtml();
	
	}
	
	function count()
	{
		$sql = 'SELECT COUNT(*) as nb FROM minichat';
		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $row[0]["nb"];
		
	}
	
	function getMessages ($max, $page)
	{

		$req_sql = '
			SELECT
				UNIX_TIMESTAMP(time) as timestamp, id_auteur, post 
			FROM
				minichat
			ORDER BY
				time
			DESC LIMIT '.$max.' OFFSET '.(($page-1)*$max);

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
			$line = new MinichatMessage(
				$row['timestamp'], 
				$this->userFactory->prepareUserFromId($row['id_auteur']), 
				$row['post'],
				$this->wiki);
			$post[] = $line;
		}
		$post = array_reverse($post);
		return $post;
	}
	
}

?>