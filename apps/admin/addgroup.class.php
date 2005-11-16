<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * Action : add a group
 * 
 * @package applications
 */
class AddGroup extends FormModel
{
	function build()
	{
		$parent_id = $_POST['parent'];
		$name = $_POST['name'];
		$annudb = $GLOBALS['config']['bdd']["annuairedb"];
		if( $parent_id > 0 )
		{
			$qry = "SELECT * FROM ".$annudb.".groups WHERE id='".$parent_id."'";
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
			}
			catch( PDOException $e )
			{
				Debug::kill( $e->getMessage() );
			}
			if( $tab = $stmt->fetch() )
			{
				unset($stmt);
				$parent = new Group($tab);
				$qry = "UPDATE ".$annudb.".groups SET `left`=`left`+2 WHERE `left` > ".$parent->getRight()." ; ";
				$qry .= "UPDATE ".$annudb.".groups SET `right`=`right`+2 WHERE `right` >= ".$parent->getRight()." ; ";
				$qry .= "INSERT INTO ".$annudb.".groups (`name`, `left`, `right`)
					VALUES ('".$name."', ".$parent->getRight().", ".($parent->getRight()+1)."); ";
				try
				{
					$this->db->exec($qry);
				}
				catch( PDOException $e )
				{
					Debug::kill( $e->getMessage() );
				}
			}
		}
		else
		{
			$qry = "SELECT MAX(`right`) `right` FROM ".$annudb.".groups";
			try
			{
				$stmt = $this->db->query($qry, PDO::FETCH_ASSOC);
				$tab = $stmt->fetch();
				unset($stmt);
			}
			catch( PDOException $e )
			{
				Debug::kill( $e->getMessage() );
			}
			$qry = "INSERT INTO ".$annudb.".groups (`name`, `left`, `right`)
				VALUES ('".$name."', ".($tab['right']+1).", ".($tab['right']+2).") ";
			try
			{
				$this->db->exec($qry);
			}
			catch( PDOException $e )
			{
				Debug::display($qry);
				Debug::kill( $e->getMessage() );
			}
		}
	}

}
?>