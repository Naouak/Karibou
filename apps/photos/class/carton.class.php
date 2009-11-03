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
    protected $all;
    protected $type;

    function __construct($db,$carton) {
        $this->db = $db;
        $this->all = $carton;
        $this->name = $carton["name"];
        $this->id = $carton["id"];
        $this->parent = $carton["parent"];
        $this->date = $carton["date"];
        $this->type="carton";
    }
}
