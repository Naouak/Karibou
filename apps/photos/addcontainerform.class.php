<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/

class AddContainerForm extends PhotosFormModel {
	public function build() {
		// We retrieve all data from the form and we filter this data to avoid problems
		$name = filter_input(INPUT_POST,"name", FILTER_SANITIZE_SPECIAL_CHARS);
		$type=filter_input(INPUT_POST,"type",FILTER_SANITIZE_SPECIAL_CHARS);
		$parent=filter_input(INPUT_POST,"parent",FILTER_SANITIZE_NUMBER_INT);
		$tags = filter_input(INPUT_POST,"tags",FILTER_SANITIZE_SPECIAL_CHARS);

		$container = containerFactory::getInstance();
		$objparent = $container->getPictureStorage($parent);


		//Now we have to update all left/right values of others containers
		$uleft = "UPDATE pictures_album SET `left`=`left`+2 WHERE `left`>:rightparent;";
		$uright = "UPDATE pictures_album SET `right`=`right`+2 WHERE `right`>=:rightparent;";
		$updateleft = $this->db->prepare($uleft);
		$updateleft->bindValue(":rightparent",$objparent->getRight());
		$updateleft->execute();
		$updateright = $this->db->prepare($uright);
		$updateright->bindValue(":rightparent",$objparent->getRight());
		$updateright->execute();

		// we create the new container (we use parent/child system AND left/right system to have both advantages of this 2 systems
		$stmt = $this->db->prepare("INSERT INTO pictures_album (`name`,`type`,`parent`,`left`,`right`) VALUES (:name,:type,:parent,:left,:right);");
		$stmt->bindValue(":name",$name);
		$stmt->bindValue(":parent",$objparent->getId());
		$stmt->bindValue(":left",$objparent->getRight());
		$stmt->bindValue(":right",$objparent->getRight()+1);
		$stmt->bindValue(":type",$type);
		$stmt->execute();

		
		$id = $this->db->lastInsertId();

		//we get all tags from parent to insert them for the new child
		$sql = $this->db->prepare("SELECT id_tag FROM pictures_album_tagged AS a WHERE id_album=:id");
		$sql->bindValue(":id",$parent);
		$sql->execute();
		$ids = $sql->fetchAll();

		

		if ($id != 0){
			$this->insertNewTags($tags,$type,$id);
			$this->insertNewTagsFromId($ids,$type,$id);
		}
		

		// this permits to Redirect to the page written in the config.xml with this argument after the submit
		$this->setRedirectArg("id",$parent);

	}
}
