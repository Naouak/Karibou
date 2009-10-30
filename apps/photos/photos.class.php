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
class photos extends Model
{
// you would have the function build() in all apps you create
	public function build()
	{
        $tags = $this->db->prepare("SELECT name from pictures_tags;");
        $tags->execute();
        $tags_array = $tags->fetchAll();
        $this->assign("tags",$tags_array);

        $albums = $this->db->prepare("SELECT * from pictures_album;");
        $albums->execute();
        $albums_array = array();
        $i=0;
        while(($album = $albums->fetch()) != false){
            $albums_array[$i]["id"]   = $album["id"];
            $objalbum = new album($this->db,$album["id"]);
            $albums_array[$i]["name"] = $objalbum->getName();
            $albums_array[$i]["date"] = $objalbum->getDate();
            $preview = $this->db->prepare("SELECT p.id,rand() as rnd FROM pictures AS p LEFT JOIN pictures_album AS pa ON p.album = pa.id WHERE pa.name = :album ORDER BY rnd  LIMIT 1;");
            $preview->bindValue(":album",$album["name"]);
            $preview->execute();
            $random = $preview->fetch();
            $albums_array[$i]["rnd"] = $random["id"];
            $i++;
        }
        $this->assign("albums",$albums_array);
	}
}
?>
