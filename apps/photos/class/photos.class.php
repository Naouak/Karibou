<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

class pictures extends AlbumBase {

	/**
	 *@var PDO
	 **/
    protected $db;
	
	/**
	 *@var String
	 **/
    protected $name;
	
	/**
	 *@var Array
	 **/
    protected $tags;

	/**
	 *@var Int
	 **/
    protected $id;

	/**
	 *@var Int
	 **/
    protected $parent;

	/**
	 *@var Array
	 **/
	protected $all;

	/**
	 *@param Int $id
	 **/
    function __construct($id) {
        $this->db=Database::instance();
        $this->id = $id;
//         $this->currentuser = $currentuser;
        $sql = $this->db->prepare("SELECT * FROM pictures where id=:id;");
        $sql->bindValue(":id",$id);
        $sql->execute();
        $pict = $sql->fetch();
		$this->all = $pict;
        $this->parent = $pict["album"];
        $this->date = $pict["date"];
    }


    public function getFileName() {
      
        }

	public function getTags() {
    }
    

}
