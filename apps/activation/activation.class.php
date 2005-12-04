<?php

class Activation extends Model
{
	function build()
	{
		$table = $GLOBALS['config']['bdd']['annuairedb'].".import_alumni";
		
		
		if( isset($_GET['key']) )
		{
			$this->assign("key", $_GET['key']);
			$qry = "SELECT * FROM $table WHERE uniqkey='".addslashes($_GET['key'])."'";
			$stmt = $this->db->query($qry);
			if( $item = $stmt->fetch(PDO::FETCH_ASSOC) )
			{
				$this->assign("item", $item);
			}
			unset($stmt);
			
		}
	}
}

?>