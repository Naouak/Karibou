<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/


/**
 * Classe photos
 *
 * @package applications
 */

// name of the class and extends permit to have an heritage of an class 
class photos extends PhotosModel
{
    // you would have the function build() in all apps you create
    public function build()
    {
        $this->assign("tags",$this->getAllTags());

        $albums = $this->db->prepare("SELECT * from pictures_album where type=:type;");
        $albums->bindValue(":type","album");
        $albums->execute();
        $albums_array = array();
        $i=0;
        while(($album = $albums->fetch()) != false){
            $objalbum = new album($this->db,$album);
            //            if ( $objalbum->can($this->currentUser,"read")){
            $albums_array[$i]["id"]   = $album["id"];
            $albums_array[$i]["name"] = $objalbum->getName();
            $albums_array[$i]["date"] = $objalbum->getDate();
            $albums_array[$i]["rnd"] = $objalbum->getRandomPicture();
            $i++;
            //          }
        }
        $this->assign("albums",$albums_array);

        $sql = $this->db->prepare("SELECT id FROM pictures_album WHERE `parent` IS NULL");
        $sql->execute();
        $slash = $sql->fetch();
        $this->assign("idslash",$slash["id"]);
    }
}
?>
