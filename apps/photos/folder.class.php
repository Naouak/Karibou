<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/

class folder extends Model {
    //je récupère l'ensemble des enfants de ce container
    $child = $this->db->prepare("SELECT * FROM pictures_album WHERE parent=:parent");
    $child->bindValue(":parent",$this->args["id"]);
    $child->execute();
    $children = $child->fetchAll();
    $container = containerFactory::getInstance();
    foreach($children as $kid){
        $k = $container->getPictureStorage($kid["id"]);
        
    }
}
