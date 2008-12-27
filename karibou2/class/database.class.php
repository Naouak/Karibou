<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/


/**
 * Classe d'abstraction à la base de données
 * @package framework
 */
Class Database extends PDO
{
	protected $db;

	public function __construct($dsn, $username, $password)
	{
		parent::__construct($dsn, $username, $password);
		parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
//		if (parent::getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
//			parent::setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
//			$this->exec("SET NAMES utf8;");
//		}
	}
	
	function query($qry)
	{
		Debug::display("PDO::query : ".$qry);
		return parent::query($qry);
	}

	function prepare($qry)
	{
		Debug::display("PDO::prepare : ".$qry);
		return parent::prepare($qry);
	}

	function exec($qry)
	{
		Debug::display("PDO::exec : ".$qry);
		return parent::exec($qry);
	}
	
}
 
?>
