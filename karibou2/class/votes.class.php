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

class VotesScoreFactory {
    protected $db;
    private static $instance;

    private function __construct() {
        $this->db =  Database::instance();
    }


    public static function getInstance() {
        if(!(self::$instance instanceof self))
            self::$instance = new self();
        return self::$instance;
    }

    public function getScore($key_id){
        $stmt = $this->db->prepare("SELECT SUM(vote), COUNT(vote) FROM votes where key_id=:key_id");
        $stmt->bindValue(":key_id",$key_id);
        $stmt->execute();
        $score = $stmt->fetch();
        return $score;
    }

    public function Voted($key_id,$userid){
        $stmt = $this->db->prepare("SELECT count(vote) FROM votes WHERE key_id=:key_id AND user=:userid");
        $stmt->bindValue(":key_id",$key_id);
        $stmt->bindValue(":userid",$userid);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0 )
            return false;
        else
            return true;
    }


}
