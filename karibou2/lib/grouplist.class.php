<?php
/**
 * @copyright 2005 JoN
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

/**
 * @package lib
 */

class GroupList extends ObjectList
{
	function offsetSet($offset, $group)
	{		if($offset)
		{			$this->data[$offset] = $group;		}		else
		{			$this->data[$group->getId()] = $group;		}	}


	function getTree()
	{
		$tree = array();
		if( count($this->data) > 0 )
		{
			reset($this->data);
			list($id, $first) = each($this->data);
			$nextLeft = $first->getLeft();
			foreach($this->data as $g)
			{
				if( $g->getLeft() == $nextLeft )
				{
					$tree[] = $this->getTreeChilds($g);
					$nextLeft = $g->getRight() + 1;
				}
			}
		}
		return $tree;
	}
	
	function getTreeChilds($group)
	{
		$childs = $this->getChilds($group);
		$item = array();
		$item['item'] = $group;
		$item['childs'] = array();
		foreach( $childs as $c )
		{
			$item['childs'][] = $this->getTreeChilds($c);
		}
		if( count($item['childs']) == 0 ) unset($item['childs']);
		return $item;
	}
	
	function getAllChilds($group)
	{
		$childs = array();
		foreach($this->data as $g)
		{
			if( ($group->getLeft() < $g->getLeft()) &&
				($g->getRight() < $group->getRight()) )
			{
				$childs[] = $g;
			}
		}
		return $childs;
	}
	
	function getChilds($group)
	{
		if( ($group->getRight() - $group->getLeft()) < 2 )
		{
			return array();
		}
		$childs = array();
		$nextLeft = $group->getLeft() + 1;
		foreach($this->data as $g)
		{
			if( $g->getLeft() == $nextLeft)
			{
				$childs[] = $g;
				$nextLeft = $g->getRight() + 1;
			}
			if( $g->getLeft() > $nextLeft )
				break;
		}
		return $childs;
	}
	
	function getGroupById($id)
	{
		foreach($this->data as $g)
		{
			if( $g->getId() == $id)
			{
				return $g;
			}
		}
		return FALSE;
	}

	function getParent($childid)
	{
		//SELECT * FROM group pere WHERE pere.left < $fils->getLeft() AND pere.right > $fils->getRight() ORDER BY pere.left
		$child = $this->getGroupById($childid);
		if (count($child)>0)
		{
			foreach ($this->data as $g)
			{
				if ($g->getLeft() < $child->getLeft() && $g->getRight() > $child->getRight())
				{
					if ( (!isset($distance)) || ($child->getLeft() - $g->getLeft() < $distance) )
					{
						$parent = $g;
						$distance = $child->getLeft() - $g->getLeft();
					}
				}
			}
			if (isset($parent))
			{
				return $parent;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

}

?>
