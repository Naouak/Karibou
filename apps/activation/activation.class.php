<?php

class Activation extends Model
{
	function build()
	{
		$table = $GLOBALS['config']['bdd']['frameworkdb'].".import_alumni";
		
		
		if( isset($_POST['key']) )
		{
			$this->assign("key", $_POST['key']);
			if(!isset($_SESSION['activation_email']))
				session_register('activation_email');
			$_SESSION['activation_email'] = $_POST['email'];

			$qry = "SELECT * FROM $table WHERE uniqkey='".addslashes($_POST['key'])."'";
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
