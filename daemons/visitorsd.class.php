<?php
/**
 * Class Visitorsd
 *
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package daemons
 **/
/*
require_once KARIBOU_CLASS_DIR.'/daemon.class.php';
 
class Visitorsd extends Daemon
{
	protected $maxAge = 300;
	protected $updated = false;
	
	function onLogin()
	{
		if(!$this->updated)
		{
			$this->updateUser();
			$this->cleanBase();
			$this->updated = true;
		}
	}
	
	function onLogout()
	{
		$sql = "DELETE FROM ".$GLOBALS['config']['bdd']['appsdb'].".connectes";
		$sql .= " WHERE idUtilisateur='".$this->currentUser->getID()."'";
		
		$res = $this->db->exec($sql);
		
		if( DB::isError($res) )
		{
			Debug::dieError(__FILE__." ligne(".__LINE__.") ".$res->getMessage());
		}
	}
	
	function onEnterPage()
	{
		if(!$this->updated)
		{
			$this->updateUser();
			$this->cleanBase();
			$this->updated = true;
		}
	}
	
	protected function updateUser()
	{
		if ( $this->currentUser->getID() == 0 ) return;
		
		$sql = "SELECT
				*
			FROM
				".$GLOBALS['config']['bdd']['appsdb'].".connectes
			WHERE
				idUtilisateur='".$this->currentUser->getID()."'";
		$res = $this->db->query($sql);
		if( DB::isError($res) )
		{
			Debug::dieError(__FILE__." ligne(".__LINE__.") ".$res->getMessage());
		}
		if($res->fetchRow())
		{
			$t = time();
			$sql = "UPDATE ".$GLOBALS['config']['bdd']['appsdb'].".connectes SET timestamp=".$t."";
			$sql .= " WHERE idUtilisateur=".$this->currentUser->getID()."";
		}
		else
		{
			$sql = "INSERT INTO ".$GLOBALS['config']['bdd']['appsdb'].".connectes";
			$sql .= " (idUtilisateur, timestamp)";
			$sql .= " VALUES (".$this->currentUser->getID().", ".time().")";
		}
		unset($res);
		$res = $this->db->exec($sql);
		
		if( DB::isError($res) )
		{
			Debug::echoDebug(__FILE__." ligne(".__LINE__.") ".$res->getMessage());
		}
	}
	
	protected function cleanBase()
	{
		$sql = "DELETE FROM ".$GLOBALS['config']['bdd']['appsdb'].".connectes";
		$sql .= " WHERE timestamp < ".(time()-$this->maxAge)."";
		
		$res = $this->db->exec($sql);
		
		if( DB::isError($res) )
		{
			Debug::display(__FILE__." ligne(".__LINE__.") ".$res->getMessage());
		}
	}
}
*/
?>
