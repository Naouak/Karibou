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
class AnnonceAdd extends FormModel
{
	public function build()
	{
		$annonce= filter_input(INPUT_POST,'newannonce',FILTER_SANITIZE_SPECIAL_CHARS);
		$price = filter_input(INPUT_POST,'price',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$expirationdate = filter_input(INPUT_POST,'expirationday',FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"@\d{4}-\d{2}-\d{2}@")));
		
		if (strlen($annonce) > 3 && ($this->currentUser->getID() > 0) && $price!==false && $expirationdate!==false) 
		{
			//Requete d'insertion
			$sql = "INSERT INTO annonce (Id_user, annonce, datetime, price, expirationdate) VALUES (:user,:annonce, NOW(), :price , :expirationdate );";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":user",$this->currentUser->getID());
			$stmt->bindValue(":annonce",$annonce);
			$stmt->bindValue(":price",$price);
			$stmt->bindValue(":expirationdate",$expirationdate);
			
			try {
				$stmt->execute();
			} catch(PDOException $e) {
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			$this->setRedirectArg("error", 1);
		}
	}
}