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

		$qry = "SELECT f.*, o.message AS oldmessage FROM flashmail f LEFT JOIN flashmail o ON o.id=f.omsgid WHERE f.to_user_id=".$this->currentUser->getID();
		
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

	public function getAllWithSentFromDB ()
	{
		$flashmails = array();

		$id = $this->currentUser->getID();

		$qry = "SELECT f.*, o.message AS oldmessage FROM flashmail f LEFT JOIN flashmail o ON o.id=f.omsgid WHERE (f.to_user_id=$id OR f.from_user_id=$id)";
		
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

		$qry = "SELECT f.*, o.message AS oldmessage FROM flashmail f LEFT JOIN flashmail o ON o.id=f.omsgid WHERE f.to_user_id=".$this->currentUser->getID()." AND f.`read`=0";
		
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
}

?>
