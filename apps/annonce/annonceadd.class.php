<?php 
/**
 * @copyright 2008 Gilles Dehaudt
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
class AnnonceAdd extends AppContentModel
{
	public function submit($parameters)
	{
		
		if (strlen($parameters["annonce"]) > 3 && ($this->currentUser->getID() > 0) && $pararmeters["date"]!==false) 
		{
			//Requete d'insertion
			$sql = "INSERT INTO annonce (Id_user, annonce, datetime, price, expirationdate) VALUES (:user,:annonce, NOW(), :price , :expirationdate );";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":user",$this->currentUser->getID());
			$stmt->bindValue(":annonce",$parameters["annonce"]);
			$stmt->bindValue(":price",$parameters["price"]);
			$stmt->bindValue(":expirationdate",$parameters["date"]);
			
			try {
				$stmt->execute();
			} catch(PDOException $e) {
				Debug::kill($e->getMessage());
			}
		}
	}
	
	public function formFields()
	{
		return array("annonce" => array("type" => "textarea", "required" =>true, "label" => _("textannonce") , "columns" => "30", "rows" => "8"), "price" => array("type" => "text", "required" => false, "label" => _("price")), "date" => array("type"=>"date", "required" => true , "label" => _("expirationdate")));
	}
}
