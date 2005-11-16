<?php
/**
  * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package apps
 **/

/**
 * @package apps
 */

class FlashMailFactory
{	
	/**
	 * @var PDO
	 */
	protected $db;
	
	/**
	 * @var flashmails
	 */
 	protected $flashmails;

	/**
	 * @param PDO $p_db
	 */
	public function __construct(PDO $db, $currentUser, $userFactory, $autoload = TRUE)
	{
		$this->db = $db;
		$this->currentUser = $currentUser;
		$this->userFactory = $userFactory;
		$this->flashmails = array();
		if ($autoload)
		{
			$this->getAllFromDB();
		}
	}
	
	/*
	 * DB function
	 */
	public function getAllFromDB ()
	{
		$flashmails = array();

		$qry = "SELECT * FROM flashmail WHERE to_user_id=".$this->currentUser->getID();
		
		try
		{
			$stmt = $this->db->query($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage() );
		}
		
		$aflashmails = $stmt->fetchAll(PDO::FETCH_ASSOC) ;
		foreach($aflashmails as $f)
		{
			$flashmails[] = new FlashMail ($this->userFactory, $f); 
			
		}
		unset($stmt);
		
		$this->flashmails = $flashmails;
	}

	public function getUnreadFromDB ()
	{
		$flashmails = array();

		$qry = "SELECT * FROM flashmail WHERE to_user_id=".$this->currentUser->getID()." AND `read`=0";
		
		try
		{
			$stmt = $this->db->query($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage() );
		}
		
		$aflashmails = $stmt->fetchAll(PDO::FETCH_ASSOC) ;
		foreach($aflashmails as $f)
		{
			$flashmails[] = new FlashMail ($this->userFactory, $f); 
			
		}
		unset($stmt);
		
		$this->flashmails = $flashmails;
	}


	/*
	 * Return methods
	 */
	public function returnUnread ()
	{
		$flashmails = array();
		foreach ($this->flashmails as $flashmail)
		{
			if (!$flashmail->isRead())
			{
				$flashmails[] = $flashmail;
			}
		}
		
		return $flashmails;
	}
	
	public function returnArchive ()
	{
		$flashmails = array();
		foreach ($this->flashmails as $flashmail)
		{
			if ($flashmail->isArchive())
			{
				$flashmails[] = $flashmail;
			}
		}
		
		return $flashmails;		
	}
	
	public function returnAll ()
	{
		return $this->flashmails;
	}
	
	/*
	 * Set methods
	 */
	public function setAsRead ()
	{
		try
		{
			$this->db->exec("UPDATE flashmail SET `read`=1 WHERE to_user_id=".$this->currentUser->getID()."");
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage() );
		}
	}
	
	public function countNew ()
	{
			$qry = "SELECT COUNT(*) cnt FROM flashmail WHERE to_user_id=".$this->currentUser->getID()." AND `read`=0";
			try
			{
				$stmt = $this->db->query($qry);
			}
			catch( PDOException $e )
			{
				Debug::kill( $e->getMessage() );
			}
			$cnt = $stmt->fetchAll();
			//unset( $stmt );
			return $cnt[0]['cnt'];
	}
	
	public function checkNew()
	{
		if ($this->countNew() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

?>