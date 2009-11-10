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
    protected $all;
    protected $type;


    function __construct($db,$album) {
        $this->db = $db;
        $this->all = $album;
        $this->id = $this->all["id"];
        $this->name = $this->all["name"];
        $this->parent = $this->all["parent"];
        $this->date = $this->all["date"];
        $this->type = "album";
    }



    public function addPicture() {
    }


    public function getAllPictures() {
        $sql = $this->db->prepare("SELECT id FROM pictures WHERE album=:album");
        $sql->bindValue(":album",$this->id);
        $sql->execute();
        $pictures = $sql->fetchAll();
        return $pictures;
    }

    public function getRandomPicture() {
        $preview = $this->db->prepare("SELECT p.id,rand() as rnd FROM pictures AS p LEFT JOIN pictures_album AS pa ON p.album = pa.id WHERE pa.name = :album AND p.album = :id ORDER BY rnd  LIMIT 1;");
        $preview->bindValue(":album",$album["name"]);
        $preview->bindValue(":id",$this->id);
        $preview->execute();
        $random = $preview->fetch();

    }
}
