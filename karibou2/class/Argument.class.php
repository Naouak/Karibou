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
 * Abstract class to use when creatin a new Argument Class handler
 * 
 * @package framework
 */
abstract class Argument
{
	protected $value;
	function __construct($value=false)
	{
		$this->setValue($value);
	}
	
	function getValue()
	{
		return $this->value;
	}
	
	function setValue($value)
	{
		$this->value = $value;
	}
	
	abstract function getUrlArgument();
	abstract function getVar($urlArgument);
}



?>
