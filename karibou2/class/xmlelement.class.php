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
class XMLElement implements ArrayAccess
{
	public $attributes;
	public $text;
	
	public function __construct()
	{
		$this->attributes = array();
	}
	
	public function addText($txt)
	{
		$this->text = $txt;
	}
	public function addAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}
	public function addChild($name, XMLElement $child)
	{
		if( !isset($this->$name) ) $this->$name = array();
		array_push($this->$name, $child);
	}
	
	public function __toString()
	{
		return $this->text;
	}

    public function __toInt()
    {
        return intval($this->text);
    }
	
	function offsetExists($offset)
	{
		if(isset($this->attributes[$offset]))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	function offsetGet($offset)
	{
		return $this->attributes[$offset];
	}
	function offsetSet($offset, $value)
	{
		if($offset)
		{
			$this->attributes[$offset] = $value;
		}
		else
		{
			$this->attributes[] = $value;
		}
	}
	function offsetUnset($offset)
	{
		unset($this->attributes[$offset]);
	}
}

?>
