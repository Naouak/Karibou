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


}

