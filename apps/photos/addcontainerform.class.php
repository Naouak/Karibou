<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/

class AddContainerForm extends FormModel {
    public function build() {
        $name = filter_input(INPUT_POST,"name", FILTER_SANITIZE_SPECIAL_CHARS);
        $type=filter_input(INPUT_POST,"type",FILTER_SANITIZE_SPECIAL_CHARS);
        $parent=filter_input(INPUT_POST,"parent",FILTER_SANITIZE_NUMBER_INT);
        $stmt = $this->db->prepare("INSERT INTO pictures_album (`name`,`type`,`parent`) VALUES (:name,:type,:parent);");
        $stmt->bindValue(":name",$name);
        $stmt->bindValue(":parent",$parent);
        $stmt->bindValue(":type",$type);
        $stmt->execute();
        
    }
}
