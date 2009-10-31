<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

class album extends AlbumBase {

    protected $db;
    protected $id;
    protected $name;
    protected $parent;
    protected $date;



    function __construct($db,$id) {
        $this->db = $db;
        $this->id = $id;
        $sql =  $this->db->prepare("SELECT * FROM pictures_album WHERE id=:id AND type=:type");
        $sql->bindValue(":id",$id);
        $sql->bindValue(":type","album");
        $sql->execute();
        $album = $sql->fetchAll();
        $this->name = $album[0]["name"];
        $this->parent = $album[0]["parent"];
        $this->date = $album[0]["date"];
    }

    public function getParent() {
        return $this->parent;
    }


    public function addPicture() {
    }

    public function can(User $user,$what) {
        $user_id = $user->getId();
        $groups = $user->getGroups($this->db);

        $perm = $this->db->prepare("SELECT * FROM album_acl WHERE id_album = :id AND permission=:perm");
        $perm->bindValue(":id",$this->id);
        $perm->bindValue(":perm",$what);
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

    public function getAllPictures() {
        $sql = $this->db->prepare("SELECT id FROM pictures WHERE album=:album");
        $sql->bindValue(":album",$this->id);
        $sql->execute();
        $pictures = $sql->fetchAll();
        return $pictures;
    }

}
