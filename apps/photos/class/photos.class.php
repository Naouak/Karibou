<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

class pictures extends AlbumBase {

    protected $db;
    protected $name;
    protected $tags;
    protected $id;
    protected $parent;

    function __construct($id) {
        $this->db=Database::instance();
        $this->id = $id;
        $this->currentuser = $currentuser;
        $sql = $this->db->prepare("SELECT * FROM pictures where id=:id;");
        $sql->bindValue(":id",$id);
        $sql->execute();
        $pict = $sql->fetch();
        $this->parent = $pict["album"];
        $this->date = $pict["date"];
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
    

}
