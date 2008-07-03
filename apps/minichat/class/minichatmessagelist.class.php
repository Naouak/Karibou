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
	protected $bbcode;
	protected $inversepostorder;

	function __construct(PDO $db, UserFactory $userFactory, $userichtext, $inversepostorder)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
		$this->inversepostorder = $inversepostorder;
	
		/* Wiki Construct */
		//$this->wiki = new wiki2xhtmlBasic();
		//$this->wiki->wiki2xhtml();
		
		
		/* BBCode Construct */
		$this->bbcode = new MinichatBBCode($userichtext);

	
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
		$max = (int) $max;
		$page = (int) $page;

		$req_sql = "
			SELECT
				UNIX_TIMESTAMP(time) as timestamp, id_auteur, post 
			FROM
				minichat
			ORDER BY
				time DESC
			LIMIT :max OFFSET :offset";

		$post = array ();
		try
		{
			$stmt = $this->db->prepare($req_sql);
			$stmt->bindValue(":max", $max, PDO::PARAM_INT);
			$stmt->bindValue(":offset", (($page-1)*$max), PDO::PARAM_INT);
			$stmt->execute();
		}
		catch( PDOException $e)
		{
			die($e->getMessage());
			Debug::kill($e->getMessage());
		}
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$line = new MinichatMessage(
				$row['timestamp'], 
				$this->userFactory->prepareUserFromId($row['id_auteur']), 
				strip_tags($row['post']),
				$this->wiki,
				$this->bbcode);
			$post[] = $line;
		}
		if (!$this->inversepostorder)
			$post = array_reverse($post);

		return $post;
	}
    
    function getMessagesFromDate ($timestamp) {
        $sql = "SELECT UNIX_TIMESTAMP(time) as timestamp, id_auteur, post FROM minichat WHERE DATE(time) = DATE(FROM_UNIXTIME(:timeStamp)) ORDER BY time DESC;";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":timeStamp", $timestamp);
            $stmt->execute();
        } catch (PDOException $e) {
            Debug::kill($e->getMessage());
        }
        $post = array();
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) )
        {
            $line = new MinichatMessage(
                                        $row['timestamp'], 
                                        $this->userFactory->prepareUserFromId($row['id_auteur']),
                                        $row['post'],
                                        $this->wiki,
					$this->bbcode);
            $post[] = $line;
        }
	if (!$this->inversepostorder)
        	$post = array_reverse($post);
        return $post;
    }
    
    function dateRange () {
        $req = "SELECT UNIX_TIMESTAMP(DATE(MIN(time))) as minDate, UNIX_TIMESTAMP(DATE(MAX(time))) as maxDate FROM minichat;";
        try {
            $stmt = $this->db->query($req);
        } catch (PDOException $e) {
            Debug::kill($e->getMessage());
        }
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array($row[0]["minDate"], $row[0]["maxDate"]);
    }
}

?>
