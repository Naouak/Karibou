<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 **/

/**
 * @package applications
 **/

class folder extends Model {
    public function build() {
		$container = containerFactory::getInstance();
		$parent = $container->getPictureStorage($this->args["id"]);
		

        $child = $this->db->prepare("SELECT *
										FROM pictures_album 
										WHERE `parent`=:parent");
        $child->bindValue(":parent",$parent->getId());
        $child->execute();
        $children = $child->fetchAll();
        
        $array = array();
        foreach($children as $kid){
            $k = $container->getPictureStorage($kid["id"]);
            $array[] = $k->getAll();
        }
        
        $this->assign("parentpath",$parent->getAllParent());
        $this->assign("children",$array);
        $this->assign("parent",$this->args["id"]);
		$this->assign("tags",$parent->getAllTags());
		$this->assign("type","carton");
    }
}
