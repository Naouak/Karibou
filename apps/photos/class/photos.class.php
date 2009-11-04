<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

class Photos extends AlbumBase {

    protected $db;
    protected $name;
    protected $tags;
    protected $id;
    protected $parent;

    function __construct($db,$id) {
        $this->db = $db;
        $this->id = $id;
        $this->currentuser = $currentuser;
        $sql = $this->db->prepare("SELECT * FROM pictures where id=:id;");
        $sql->bindValue(":id",$id);
        $sql->execute();
        $pict = $sql->fetchAll();
        $this->parent = $pict[0]["album"];
    }


    public function getFileName() {
        $path="/";
        $parent = $this->parent;
        while($parent != NULL){
            $container = containerFactory::getInstance();
            $objalb = $container->getPictureStorage($parent);
            $parent = $objalb->getParent();
            $path .= $objalb->getName . "/";
        }
    }
    
    public function canRead(User $user) {
        $user_id = $user->getId();
        $groups = $user->getGroups($this->db);
        
        $perm = $this->db->prepare("SELECT * FROM pictures_album_acl WHERE id_album = :id");
        $perm->bindValue(":id",$this->album);
        $perm->execute();
        $acl = $perm->fetchAll();
        
        foreach($acl as $row) {
            if (in_array($row["group"],$groups)) {
                return true;
            }
            if ($row["user"] == $user_id) {
                return true;
            }
        }
        
        return false;
    }

}
