<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

class carton extends AlbumBase {
    protected $db;
    protected $id;
    protected $name;
    protected $parent;
    protected $date;

    function __construct($db,$id) {
        $this->db = $db;
        $this->id = $id;
        $sql = $this->db->prepare("SELECT * FROM pictures_album WHERE id=:id AND type=:type");
        $sql->bindValue(":id",$this->id)
    }
}
