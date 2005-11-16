<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * @package framework
 */
abstract class Listener
{
	/**
	 * @var PDO
	 */
	protected $db;
	
	/**
	 * @var UtilisateurCourant
	 */
	protected $currentUser;
	
	/**
	 * @var UserFactory
	 */
	protected $userFactory;
	
	/**
	 * @var AppList
	 */
	protected $appList;

	/*
	 * Objet de gestion des ancres
	 */
	protected $hookManager;

	/*
	 * Objet de gestion des langues
	 */
	protected $languageManager;

	/*
	 * Objet de gestion des événements
	 */
	protected $eventManager;

	final function __construct(
		PDO $p_db,
		UserFactory $userFactory,
		AppList $appList,
		HookManager $hookManager,
		LanguageManager $languageManager,
		EventManager $eventManager,
		MessageManager $messageManager
		)
	{
		$this->db = $p_db;
		$this->userFactory = $userFactory;
		$this->currentUser = $userFactory->getCurrentUser();
		$this->appList = $appList;
		$this->hookManager = $hookManager;
		$this->languageManager = $languageManager;
		$this->eventManager = $eventManager;
		$this->messageManager = $messageManager;
	}

	abstract function eventOccured(Event $event) ;

}

?>
