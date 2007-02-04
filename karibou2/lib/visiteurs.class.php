<?php
/**
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

/**
 * 
 * 
 * @package lib
 **/
Class Visiteurs
{
	protected $db;
	protected $connectedArray;
	protected $count;
	protected $users;
	
	
	function __construct($db)
	{
		$this->db = $db;
		$this->connectedArray = false;
		$this->count = false;
	}
	
	function getConnectedUsers()
	{
		if($this->connectedArray == false)
		{
			$sql = "SELECT idUtilisateur, timestamp FROM ".$GLOBALS['config']['bdd']["appsdb"].".connectes";
			$sql .= " GROUP BY idUtilisateur";

			$this->connectedArray = array();
			foreach( $this->db->query($sql) as $tab )
			{
				$this->connectedArray[] = $tab['idUtilisateur'];
			}
		}
		reset($this->connectedArray);
		
		return $this->connectedArray;
	}
	
	function countConnected()
	{
		if($this->count === false)
		{
			try
			{
				$stmt = $this->db->prepare("SELECT COUNT(*) as count FROM connectes");
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			$one = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->count = $one["count"];
			unset($stmt);
		}
		return $this->count;
	}
	
	function getLinkConnectedUsers()
	{		
		$html = "<a href=\"/LesVisiteurs\" ";
		$html .= "target=\"pitite\" ";
		$html .= "OnClick=\"window.open('','pitite','scrollbars=yes,width=450,height=240,statusbar=no,menubar=no,toolbar=no,resizable=yes,top=50,left=50');\">";
		$html .= $this->count;
		if($this->count > 1)
		{
			$html .= " Connectés</a>";
		}
		else
		{
			$html .= " Connecté</a>";
		}
		return $html;
	}
}

?>
