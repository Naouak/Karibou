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

    public function getName(){
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->db;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getType() {
        return $this->type;
    }

    public function getAll() {
        return $this->all;
    }

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

    // on passe en paramètre si on veut vérifier les permissions en écriture ou en lecture
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

}

