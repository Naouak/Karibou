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

	/**
	 * function return the name
	 **/

	public function getName(){
        return $this->name;
    }
	/**
	 * function return the Id
	 **/

	public function getId() {
        return $this->id;
    }

	/**
	 * function return the Date
	 **/

	public function getDate() {
        return $this->db;
    }

	/**
	 * function return the parent
	 **/

	public function getParent() {
        return $this->parent;
    }

	/**
	 * function return the type
	 **/

	public function getType() {
        return $this->type;
    }

	/**
	 * function return the array with all informations 
	 **/

	public function getAll() {
        return $this->all;
    }

	/**
	 * function return the all parents from slash to here
	 **/

	public function getAllParent() {
        $path=array();
        $parent=$this->parent;
        while( $parent!=NULL){
            $container = containerFactory::getInstance();
            $objalb = $container->getPictureStorage($parent);
            $link = array($objalb->getName(),$parent,$objalb->getType());
            $parent = $objalb->getParent();
            array_unshift($path,$link);
        }
        $path[]=array($this->name,$this->id,$this->type);
        return $path;
    }

    /**
	 *@param String $perm 
	 * function return if we can write or read this container 
	 */
	public function can($perm){
        $currentuser = UserFactory::instance()->getCurrentUser();
        $db = Database::instance();
        //PDO statement doesn't accept to prepare array, so we will use some joins ...
        $perm = $db->prepare("
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

