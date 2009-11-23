<?php
/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
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
	private static $instance = null;
	private $driver = null;

	const MYSQL = 1;
	const POSTGRES = 2;

	public function initialize($dsn, $username, $password)
	{
		if (Database::$instance == null)
			Database::$instance = new Database($dsn, $username, $password);
	}

	public static function instance()
	{
		return Database::$instance;
	}

	public function driver()
	{
		return $this->driver;
	}

	public function __construct($dsn, $username, $password)
	{
		if (strpos("mysql", $dsn) !== FALSE)
			$this->driver = MYSQL;
		else if (strpos("pgsql", $dsn) !== FALSE)
			$this->driver = POSTGRES;
		parent::__construct($dsn, $username, $password);
		parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	}
	
	public function query($qry)
	{
		Debug::display("PDO::query : ".$qry);
		return parent::query($qry);
	}

	public function prepare($qry)
	{
		Debug::display("PDO::prepare : ".$qry);
		return parent::prepare($qry);
	}

	public function exec($qry)
	{
		Debug::display("PDO::exec : ".$qry);
		return parent::exec($qry);
	}
	
}
 
?>
