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

class FlashMail
{	
	/**
	 * @var flashmails
	 */
 	protected $info;
	
	/**
	 * @var author
	 */
	protected $author;
	
	/**
	 * @var UserFactory $userFactory
	 */
	protected $userFactory;

	/**
	 * @param UserFactory $userFactory
	 * @param Array $tab
	 */
	public function __construct($userFactory, $tab)
	{
		$this->userFactory = $userFactory;
		$this->info = $tab;
		$this->author = $this->userFactory->prepareUserFromId($this->getInfo("from_user_id"));
	}
	
	public function getInfo($key)
	{
		if (isset($this->info[$key]))
		{
			return $this->info[$key];
		}
		else
		{
			return FALSE;
		}
	}

	public function getId ()
	{
		return $this->getInfo("id");
	}
	
	public function getDate ()
	{
		return $this->getInfo("date");
	}

	public function getAuthorId ()
	{
		return $this->author->getId();
	}

	public function getAuthorLogin ()
	{
		return $this->author->getLogin();
	}
	
	public function getAuthorSurname ()
	{
		return $this->author->getSurname();
	}
	
	public function getAuthorDisplayName ()
	{
		if ($this->author->getSurname() != "")
		{
			return $this->author->getSurname();
		}
		elseif ($this->author->getFirstname() != "")
		{
			return $this->author->getFirstname();
		}
		else
		{
			return $this->author->getLogin();
		}
	}
	
	public function getMessage()
	{
		return $this->getInfo("message");
	}

    public function getOldMessage()
    {
        return $this->getInfo("oldmessage");
    }

	public function isRead ()
	{
		if ($this->getInfo("read") == '0')
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function isArchive ()
	{
		if ($this->getInfo("archive") == '0')
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

}

?>
