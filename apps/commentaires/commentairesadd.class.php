<?php

/**
 * @copyright 2009 Gilles DEHAUDT
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
*/

class CommentairesAdd extends FormModel {
    public function build() {
        $comment = filter_input(INPUT_POST,"comment",FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
        $stmt = $this->db->prepare("INSERT INTO comment (`user`, `key_id`,`comment`) VALUES (:user,:id,:comment) ");
        $stmt->bindValue(":user",$this->currentUser->getId());
        $stmt->bindValue(":id",$id);
        $stmt->bindValue(":comment",$comment);
        $stmt->execute();
    }
}

