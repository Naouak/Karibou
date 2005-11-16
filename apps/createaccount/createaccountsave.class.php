<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 * @copyright 2005 JoN
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
class CreateAccountSave extends FormModel
{
	public function build()
	{
		if( isset($_POST["username"], $_POST["password1"], $_POST["password2"]) && ($_POST["password1"]==$_POST["password2"]) )
		{
			$annudb = $GLOBALS['config']['bdd']["annuairedb"];
			$qry = "INSERT INTO ".$annudb.".users (`login`, `password`) VALUES ('".$_POST["username"]."', PASSWORD('".$_POST["password1"]."') ) ";
			try
			{
				$this->db->exec($qry);
			}
			catch (PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
	}
}

?>