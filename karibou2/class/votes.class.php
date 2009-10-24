<?php
/*
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 */

/*
 * objet votes
 * @package framework
 */

class Score {
    protected $db;
    protected $key_id;
    protected $userid;

    function __construct($db,$key_id,$userid) {
        $this->db = $db;
        $this->key_id = $key_id;
        $this->userid = $userid;
    }

    public function getScore(){
        $stmt = $this->db->prepare("SELECT SUM(vote), COUNT(vote) FROM votes where key_id=:key_id");
        $stmt->bindValue(":key_id",$this->key_id);
        $stmt->execute();
        $score = $stmt->fetch();
        return $score;
    }

    public function Voted(){
        $stmt = $this->db->prepare("SELECT count(vote) FROM votes WHERE key_id=:key_id AND user=:userid");
        $stmt->bindValue(":key_id",$this->key_id);
        $stmt->bindValue(":userid",$this->userid);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0 )
            return false;
        else
            return true;
    }

    
}
