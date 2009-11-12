<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

/**
 * album class from photos apps
 * @package Application
 */

class album extends AlbumBase {

	/**
	 *@var PDO
	 */
    protected $db;

	/**
	 *@var int
	 */
    protected $id;

	/**
	 *@var string
	 */
    protected $name;

	/**
	 *@var int
	 */
    protected $parent;

	/**
	 *@var date
	 */
    protected $date;

	/**
	 * This contain all you want about the album
	 *@var array
	 */
    protected $all;

	/**
	 *@var string
	 */
    protected $type;


	/**
	 *@param PDO $db
	 *@param array $album
	 */

    function __construct($db,$album) {
        $this->db = $db;
        $this->all = $album;
        $this->id = $this->all["id"];
        $this->name = $this->all["name"];
        $this->parent = $this->all["parent"];
        $this->date = $this->all["date"];
        $this->type = "album";
    }



    public function addPicture() {
    }

	/**
	 * This function returns an array with the id of all pictures from this album
	 */

    public function getAllPictures() {
        $sql = $this->db->prepare("SELECT id FROM pictures WHERE album=:album");
        $sql->bindValue(":album",$this->id);
        $sql->execute();
        $pictures = $sql->fetchAll();
        return $pictures;
    }

	/**
	 * This function return a random picture of this album
	 */
	
    public function getRandomPicture() {
        $preview = $this->db->prepare("SELECT p.id,rand() as rnd FROM pictures AS p LEFT JOIN pictures_album AS pa ON p.album = pa.id WHERE pa.name = :album AND p.album = :id ORDER BY rnd  LIMIT 1;");
        $preview->bindValue(":album",$album["name"]);
        $preview->bindValue(":id",$this->id);
        $preview->execute();
        $random = $preview->fetch();

    }
}
