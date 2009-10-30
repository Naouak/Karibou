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
        if(abs($this->args["votes"])==1){
            $sql= $this->db->prepare("INSERT INTO votes (key_id,user,vote) VALUES (:id,:user,:vote)");
            $sql->bindValue(":id",$this->args["id"]);
            $sql->bindValue(":user",$this->currentUser->getId());
            $sql->bindValue(":vote",$this->args["votes"]);
            $sql->execute();
        }
        else {
            throw new Exception("Qui cherche à contourner le système de vote, allez on se dénonce ... ");
        }
    }
}
