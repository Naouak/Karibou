<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */

class PermAppliPost extends FormModel
{
	function build()
	{
		$perm_group = array();
		foreach($_POST as $name => $value)
		{
			if( preg_match("/group_([0-9]+)/", $name, $match) && ($value != "_DEFAULT_") )
			{
				$perm_group[$match[1]] = $value;
			}
			else if ($name == "appli")
			{
				$appli = $value;
			}
		}
		
		$qry = "DELETE FROM permissions_group WHERE appli='".$appli."' ; ";
		if( count($perm_group) > 0 )
		{
			$qry .= "INSERT INTO permissions_group (group_id, appli, permission) VALUES ";
			$first = true;
			foreach($perm_group as $id => $perm)
			{
				if( !$first ) $qry .= " , ";
				$qry .= "(".$id.", '".$appli."', '".$perm."')";
				$first = false;
			}
			$qry .= " ; " ;
		}
		$this->db->exec($qry);
	}
}


?>