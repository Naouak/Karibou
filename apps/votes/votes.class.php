<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/


/**
 * Classe votes
 *
 * @package applications
 */


class Votes extends Model {
    public function build() {
        $sql= $this->db->prepare("INSERT INTO votes (key_id,user,vote) VALUES (:id,:user,:vote)");
        $sql->bindValue(":id",$this->args["id"]);
        $sql->bindValue(":user",$this->currentUser->getId());
        $sql->bindValue(":vote",$this->args["votes"]);
        $sql->execute();
    }
}
