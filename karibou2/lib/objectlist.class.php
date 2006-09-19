<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

/**
 * Gestion de listes d'objets
 *
 * @package lib
 */
class ObjectList implements ArrayAccess, Iterator
{
	/**
	 * @var Array
	 */
	public $data;
	
	/**
	 * A switch to keep track of the end of the array
	 */
	private $valid = FALSE;
	
	function __construct()
	{
		$this->data = array();
	}

	/**
	 * Return the array "pointer" to the first element
	 * PHP's reset() returns false if the array has no elements
	 */
	function rewind()
	{
		$this->valid = (FALSE !== reset($this->data));
	}
	
	/**
	 * Return the current array element
	 */
	function current()
	{
		return current($this->data);
	}
	
	/**
	 * Return the key of the current array element
	 */
	function key()
	{
		return key($this->data);
	}
	
	/**
	 * Move forward by one
	 * PHP's next() returns false if there are no more elements
	 */
	function next()
	{
		$this->valid = (FALSE !== next($this->data));
	}
	
	/**
	 * Move backward by one
	 * PHP's prev() returns false if there are no more elements
	 */
	function prev()
	{
		$this->valid = (FALSE !== prev($this->data));
	}
	
	
	/**
	 * Is the current element valid?
	 */
	function valid()
	{
		return $this->valid;
	} 


	function offsetExists($offset)
	{
		if(isset($this->data[$offset]))
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
		return $this->data[$offset];
	}
	
	function offsetSet($offset, $value)
	{
		if($offset)
		{
			$this->data[$offset] = $value;
		}
		else
		{
			$this->data[] = $value;
		}
	}
	
	function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}
	
	/**
	 * Tri la liste avec une fonction personnalisée
	 *
	 * La fonction peut être dans un Objet : array($this, "nomDeFonction")
	 * renvoie "false" si le tri a échoué
	 *
	 * @param function $function nom de la fonction qui servira de trie
	 */
	function sort($function)
	{
		$this->rewind();
		return usort($this->data, $function);
	}
	
	function sortby($by)
	{
		$this->rewind();
		var_dump($this->data);
		die;
		return sort($this->data, $function);
	}
	
	/**
	 * Filtre la liste avec une fonction personnalisée
	 *
	 * renvoie une ObjectList filtrée par la fonction
	 *
	 * @param function $function nom de la fonction filtre
	 * @return ObjectList
	 */
	function filter($function)
	{
		$filtered_tab = array_filter($this->data, $function);
		return new ObjectList($filtered_tab);
	}
	
	/**
	 * Compte le nombre d'enregistrements
	 *
	* @return int
	 */
	function count()
	{
		return count($this->data);
	}
	
	/**
	 * Fusionne 2 objectlists
	 *
	* @return int
	 */
	function merge($objectlist)
	{
		$this->data = array_merge($this->data, $objectlist->data);
	}
	
	function compare_date($a, $b) 
	{
		if ($a == $b) {
		  return 0;
		}
		$datea = $a->o_start->getDate();
		$dateb = $b->o_start->getDate();
		if ($datea < $dateb)
		{
			return -1;
		}
		else
		{
			return 1;
		}
	}
}

?>