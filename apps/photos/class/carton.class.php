<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

/**
 * Classe de base pour la gestion des cartons dans l'application photos : les cartons ne sont destinés qu'à contenir d'autres cartons ou des albums mais pas de photos
 *@package Application
 */

class carton extends AlbumBase {
	/**
	 * @var PDO 
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
    protected $left;
	/**
	 *@var int
	 */
    protected $right;
	/**
	 *@var date
	 */
    protected $date;
	/**
	 *@var array
	 */
    protected $all;
	/**
	 *@var string
	 */
    protected $type;

    function __construct($db,$carton) {
        $this->db = $db;
        $this->all = $carton;
        $this->name = $carton["name"];
        $this->id = $carton["id"];
        $this->date = $carton["date"];
        $this->type="carton";
		$this->left=$carton["left"];
		$this->right=$carton["right"];
    }
}
