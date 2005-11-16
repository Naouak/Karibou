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
 * User Group
 * @package framework
 **/
class Group
{
	protected $id;
	protected $name;
	protected $left;
	protected $right;
	protected $used;
	
	public function __construct()
	{
		$args = func_get_args();
		if( (count($args) == 1) && is_array($args[0]) )
		{
			$tab = $args[0] ;
			$this->id = $tab['id'] ;
			$this->name = $tab['name'] ;
			$this->left = $tab['left'] ;
			$this->right = $tab['right'] ;
		}
		else if( count($args) == 4 )
		{
			$this->id = $args[0] ;
			$this->name = $args[1] ;
			$this->left = $args[2] ;
			$this->right = $args[3] ;
		}
		else
		{
			Debug::kill("Group::__constructor : invalid number of argument");
		}
	}
	
	public function getId()
	{
		return $this->id;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getLeft()
	{
		return $this->left;
	}
	public function getRight()
	{
		return $this->right;
	}
}
 
?>