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
 * Classe d'abstraction des statements
 * @package framework
 */
Class DatabaseStatement extends PDOStatement
{

	public function __construct()
	{

	}
	
	public function execute ()
	{
		Debug::display("PDOStatement::execute");
		return parent::execute();
	}
	
}
 
?>