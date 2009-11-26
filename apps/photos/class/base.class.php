<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 *-
 * @package apps
 **/

abstract class AlbumBase {
	protected $name;
	protected $id;
	protected $date;
	protected $parent;
	protected $type;
	protected $all;
	protected $left;
	protected $right;
	protected $db;

	public function __construct() {
		$db = Database::instance();
	}

	/**
	 * function returns the name
	 **/

	public function getName(){
        return $this->name;
    }
	/**
	 * function returns the Id
	 **/

	public function getId() {
        return $this->id;
    }

	/**
	 * function returns the Date
	 **/

	public function getDate() {
        return $this->date;
    }

	/**
	 * function returns the parent
	 **/

	public function getParent() {
        return $this->parent;
    }

	/**
	 * Function returns left
	 **/

	public function getLeft() {
		return $this->left;
	}

	/**
	 * Function returns right
	 **/

	public function getRight() {
		return $this->right;
	}

	/**
	 * function returns the type
	 **/

	public function getType() {
        return $this->type;
    }

	/**
	 * function returns the array with all informations 
	 **/

	public function getAll() {
        return $this->all;
    }

	/**
	 * function returns the all parents from slash to here
	 **/

	public function getAllParent() {
        $sql = $this->db->prepare("SELECT * FROM pictures_album WHERE `left` <= :left AND `right` >= :right;");
		$sql->bindValue(":left",$this->left);
		$sql->bindValue(":right",$this->right);
		$sql->execute();
        return $sql->fetchAll();
    }

    /**
	 *@param String $perm 
	 * function returns if we can write or read this container 
	 */
	public function can($perm){
        $currentuser = UserFactory::instance()->getCurrentUser();
        //PDO statement doesn't accept to prepare array, so we will use some joins ...
        $perm = $this->db->prepare("
            SELECT
                *
            FROM
                pictures_album_acl AS p
            LEFT JOIN
                ".$GLOBALS['config']['bdd']['frameworkdb'].".group_user AS g ON (p.user = :user AND g.group_id=p.`group`
            WHERE
                id_album=:album
            AND (
                    (p.`group` IS NULL AND p.user=:user)
                OR
                    (p.user IS NULL AND g.group_id=p.group)
            )
            ORDER BY (user IS NOT NULL); ");
        $perm->bindValue(":album",$this->id);
        $perm->bindValue(":user",$currentuser->getID());
        $perm->execute();
        $acl = $perm->fetchAll();
        if ($perm == "read") {
            if (isset($acl[0][0])){
                return true;
            }
            else {
                return false;
            }
        }
        elseif ($perm == "write") {
            if (!isset($acl[0][0])){
               foreach ($acl as $permission) {
                    if ( $permission["droit"] == "read" && $permission["user"] != NULL){
                        return false;
                    }
                    elseif ($permission["droit"] == "write"){
                        return true;
                    }
                    else {
                        return false;
                    }
                }
            }
        }
        else {
            // what the hell is this permission ?
        }
    }

	/**
	 * function returns All Tags from this container or pictures
	 */


	public function getAllTags(){
		if($this->type=="album" || $this->type=="carton"){
			$sql = $this->db->prepare("SELECT t.name FROM pictures_album_tagged AS p LEFT JOIN pictures_tags as t on t.id = p.id_tag where p.id_album=:id");
		}
		elseif($this->type=="photos"){
			$sql = $this->db->prepare("SELECT t.name FROM pictures_tagged AS p LEFT JOIN pictures_tags AS t on t.id=p.tag where p.pict=:id");
		}
		$sql->bindValue(":id",$this->id);
		$sql->execute();
		
		return $sql->fetchAll();
	}
}

