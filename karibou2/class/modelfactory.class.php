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
 * Une liste de pages
 *
 * @todo inherit from a Factory ?
 * @package framework
 */ 

class ModelFactory
{
	protected $db;
	protected $userFactory;
	protected $appList;
	protected $hookManager;
	protected $eventManager;
	protected $messageManager;
	protected $data;
	protected $smarty;
	
	function __construct(
		ModelBuilder $modelbuilder,
		PDO $db,
		UserFactory $userFactory,
		AppList $appList,
		HookManager $hookManager,
		EventManager $eventManager,
		MessageManager $messageManager,
		KSmarty $smarty
		 )
	{
		$this->modelbuilder = $modelbuilder;
		$this->db = $db;
		$this->userFactory = $userFactory;
		$this->hookManager = $hookManager;
		$this->eventManager = $eventManager;
		$this->messageManager = $messageManager;
		$this->appList = $appList;
		$this->smarty = $smarty;
		$this->data = array();

	}
	
	/**
	 * Fonction d'ajout dans la pile
	 */
	function getModel($model, $appname, $templatedir, $permission, $args=array())
	{
		$uniq_id = md5($model.serialize($args));
		
		if( !isset($this->data[$uniq_id]) )
		{
			$this->data[$uniq_id] = new $model($this->db, $appname, $this->userFactory, $this->appList, 
				$templatedir, $this->hookManager, $this->eventManager, 
				$this->messageManager, $this->smarty, $permission, $args);
			$this->data[$uniq_id]->smarty->caching = false;
			$this->modelbuilder[] = $this->data[$uniq_id];
		}
		
		return $this->data[$uniq_id];
	}

}

?>