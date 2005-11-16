<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once dirname(__FILE__).'/kpermissions.class.php'; 

/**
 * @package framework
 */
class PermissionsFactory
{
	/**
	 * @var PDO
	 */
	protected $db;

	/**
	 * @param PDO $db
	 */
	function __construct(PDO $db)
	{
		$this->db = $db;
	}
	
	/**
	 * Récupére les permissions sur les applis
	 * si collision entre droits perso et clubs on garde le niveau utilisateur
         *
	 * @param UtilisateurSimple $user
	 */
	function getPermissions(User $user)
	{
		$perm = new KPermissions;
		if( $user->getID() == 0 )
		{
		}
		else
		{
			$annudb = $GLOBALS['config']['bdd']['annuairedb'];

			$qry_groups = $user->getGroupTreeQuery();
			// On récupère les droits des groupes, tries allant du pere au fils
			$qry = "
				SELECT
					pg.appli,
					pg.permission
				FROM 
					permissions_group pg ,
					".$annudb.".groups g
				WHERE
					g.id = pg.group_id AND
					( pg.group_id IN (".$qry_groups.") )
				ORDER BY 
					g.left ASC ";

			// On ecrit les droits dans l'objet permissions
			// (comme on a trie par parent on ecrase les droits du pere par ceux du fils)
			try
			{
				$stmt = $this->db->query($qry) ;
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}
			while ($tab = $stmt->fetch(PDO::FETCH_ASSOC) )
			{
				$perm->set($tab['appli'], $tab['permission']);
			}
			unset($stmt);
			// pareil, on récupère les droits spécifiques à l'utilisateur
			$qry = "
				SELECT
					appli, permission
				FROM 
					permissions_user
				WHERE
					( user_id = '" . $user->getID() . "' ) ";
			// et on ecrase les droits des groupes par ceux de l'utilisateur
			$stmt = $this->db->query($qry) ;
			while ($tab = $stmt->fetch(PDO::FETCH_ASSOC) )
			{
				$perm->set($tab['appli'], $tab['permission']);
			}
			unset($stmt);
		}
		return $perm;
	}
	
}

?>
