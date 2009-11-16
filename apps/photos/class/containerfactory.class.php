<?php 
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 **/

/**
 *
 * @package applications
 **/

class containerFactory {

    private static $instance;

    private $db;

    private function __construct() {
        $this->db= Database::instance();
    }


    public static function getInstance() {
        if (self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;

    }

	/**
	 *@param Int $id
	 * function returns an carton or album object depends of the id
	 **/
    public function getPictureStorage($id) {
        $sql = $this->db->prepare("SELECT * FROM pictures_album WHERE id=:id");
        $sql->bindValue(":id",$id);
        $sql->execute();
        $container = $sql->fetch();
        if ($container["type"] == "carton") {
            return new carton($this->db,$container);
        }
        elseif($container["type"] == "album"){
            return new album($this->db,$container);
        }
    }

}
