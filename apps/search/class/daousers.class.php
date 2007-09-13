<?php 
/**
 * @copyright 2007 Charles Anssens
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class DAOUsers extends DAOElement
{
	protected $db;
	protected $userFactory;
	
	public function findFromKeyWords($keywords) {

		
		
		$userswho = array();
		$userswho = $this->userFactory->getUsersFromSearch($keywords);

		return $userswho;
    }
}

?>