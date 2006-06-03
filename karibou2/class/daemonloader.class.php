<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Daemon List
 * @package framework
 */
class DaemonLoader
{
	/**
	 * @var PDO
	 */
	protected $db;
	
	/**
	 * @var UserFactory
	 */
	protected $userFactory;
	/**
	 * @var AppList
	 */
	protected $applist;
	/**
	 * @var LanguageManager
	 */
	protected $languageManager;
	/**
	 * @var HookManager
	 */
	protected $hookManager;
	/**
	 * @var EventManager
	 */
	protected $eventManager;
	/**
	 * @var MessageManager
	 */
	protected $messageManager;
	
	/**
	 * @param PDO $db
	 * @param UserFactory $userFactory
	 */ 
	public function __construct(PDO $db, UserFactory $userFactory, AppList $applist,
		LanguageManager $languageManager, HookManager $hookManager, EventManager $eventManager, MessageManager $messageManager)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
		$this->applist = $applist;
		$this->languageManager = $languageManager;
		$this->hookManager = $hookManager;
		$this->eventManager = $eventManager;
		$this->messageManager = $messageManager;
	}

	public function loadDaemonDir($dirname, $configfile="config.xml")
	{
		$files = glob($dirname.'/*');
		foreach( $files as $f )
		{
			if( is_dir($f) && is_file($f."/".$configfile) )
			{
				$this->loadDaemon(basename($f) , $f."/".$configfile);
			}
		}
	}
	
	public function loadDaemon($name, $configfile)
	{
		$xmlconfig = new XMLCache( KARIBOU_CACHE_DIR.'/daemon_'.$name );
		$xmlconfig->loadFile( $configfile );
		$xml = $xmlconfig->getXML();
		
		$basedir = dirname($configfile);
		
		if( isset($xml->load) )
		{
			foreach($xml->load as $load)
			{
				ClassLoader::add($load['class'], $basedir.'/'.$load['file']);
			}
		}
		
		if( isset($xml->listen) )
		{
			foreach($xml->listen as $listen)
			{
				$event = $listen['event'];
				$class = $listen['class'];
				$listener = new $class(
					$this->db,
					$this->userFactory,
					$this->applist,
					$this->hookManager,
					$this->languageManager,
					$this->eventManager,
					$this->messageManager
					);
				$this->eventManager->addListener($event, $listener);
			}
		}
	}
	
}

?>
