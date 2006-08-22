<?php 
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class DAOFactory
{
	static protected $db;
	static protected $userFactory;

	static public function init(PDO $db, UserFactory $userFactory)
	{
		DAOFactory::$db = $db;
		DAOFactory::$userFactory = $userFactory;
	}
	
	static public function create($type)
	{
		$daoClassName = 'DAO'.$type;
		if (class_exists($daoClassName))
		{
			return new $daoClassName(DAOFactory::$db, DAOFactory::$userFactory);
		}
		else
		{
			Debug::kill('Bad DAO Type');
		}
	}
}

?>