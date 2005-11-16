<?php

class NetCVContent {

	var $child;
	var $depth;
	var $id;
	var $infos;
	var $ordering;
	var $parent_id;
	var $resume_id;

	var $ubb;

	function __construct($db, $resume_id, $id = NULL, $parent_id=NULL, $depth=NULL, $ordering=NULL, $infos=NULL) {

		$this->depth 		= $depth;
		$this->id 			= $id;
		$this->infos 		= $infos;
		$this->ordering 	= $ordering;
		$this->parent_id 	= $parent_id;
		$this->resume_id 	= $resume_id;
		
		$this->db			= $db;
   }
   
   
   //Methode creant le premier element (l'element racine) 
	function createFirstElement () {
			$cvInsertElementReq = "
			INSERT INTO netcv_resumes
			  (resume_id, id, parent_id, infos, ordering, date_creation, date_modification)
			VALUES  ('".$this->resume_id."',0,0,'','', '".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$cvInsertElementRes = $this->db->prepare($cvInsertElementReq);
			$cvInsertElementRes->execute();
	}
    
   //fonction retournant l'enfant par rapport à l'identifiant passé en paramètre
   function returnChildById ($id) {
   	if ( (count($this->child) > 0) && ($id != "")) {
   		foreach($this->child as $child) {
   			if ($child->id == $id) {
	   			return $child;
   			}
   		}
   	} else {
   		return FALSE;
   	}
   	return FALSE;
   }
   
   function getVar($var) {
     return $this->$var;
   }
   
   function getVarXHTML($var)
	{
        $wiki = new wiki2xhtmlBasic();
        // initialisation variables
        $wiki->wiki2xhtml();
        $content = $wiki->transform($this->$var);
        return $content;
   }

	function getChildSQL () {
		if ($this->id == NULL) {
            //Selection du contenu de l'enregistrement racine
	       	$cvRootSelectReq = "
               SELECT *
               FROM netcv_resumes
               WHERE resume_id = '$this->resume_id'
                     AND id = 0";
            $cvRootSelectRes = $this->db->prepare($cvRootSelectReq);
            $cvRootSelectRes->execute();
            $cvRootSelectRows = $cvRootSelectRes->fetchAll();

			if (count($cvRootSelectRows) != 0) {
				$this->depth		= 0;
				$this->id			= $cvRootSelectRows[0]["id"];
				$this->infos		= $cvRootSelectRows[0]["infos"];
				$this->ordering	= $cvRootSelectRows[0]["ordering"];
				$this->parent_id	= $cvRootSelectRows[0]["parent_id"];
			} else {
				$this->depth 		= 0;
				$this->id			= 0;
				$this->infos		= "myResumeFromArray";
				$this->ordering 	= "";
				$this->parent_id 	= NULL;
			}
		}
		
        $cvContentSelectReq = "
            SELECT *
            FROM netcv_resumes
            WHERE parent_id = '$this->id'
                  AND resume_id = '$this->resume_id'
                  AND id <> parent_id
            GROUP BY id";
        $cvContentSelectRes = $this->db->prepare($cvContentSelectReq);
        $cvContentSelectRes->execute();
        $cvContentSelectRows = $cvContentSelectRes->fetchAll();
        
        $i = 0;
        foreach($cvContentSelectRows as $cvContentSelectRow) {
				$myResume[$i] = new NetCVContent($this->db, $this->resume_id, $cvContentSelectRow["id"], $cvContentSelectRow["parent_id"], ($this->depth+1), $cvContentSelectRow["ordering"], $cvContentSelectRow["infos"]);
				$myResume[$i]->getChildSQL();
				$i++;
        }

		$numRows = $i;
		if(isset($myResume)) {
			$this->child = $myResume;
		}


		$this->reorderChildList();
		
        //Retourne le nombre d'enregistrement de la section
        return $numRows;
	}
	
	function reorderChildList () {
		if ( (count ($this->child) > 0) && isset($this->ordering) && ($this->ordering != "")) {
			$ordering = split (":",$this->ordering);

			$newChildList = array();
	
			//Ajout de la variable de verification de traitement (processed)
			foreach ($this->child as $key => $child) {
				$child->processed = FALSE;
			}

			//Création de la nouvelle liste triée
			foreach ($ordering as $key => $value) {
				foreach ($this->child as $listId => $child) {
					//Verification que l'enfant n'a pas été traité
					if (!$child->processed) {
						//Si l'identifiant de l'enfant correspond a l'identifiant dans la liste, on assigne
						if ($child->id == $value) {
							$newChildList[] = $child;
							$child->processed = TRUE;
						}
					}
				}

			}
			
			//Ajout des elements restants (eventuellement non presents dans le tableau de tri)
			foreach ($this->child as $listId => $child) {
				if (!$child->processed) {
					$newChildList[] = $child;
				}
			}

			unset($this->child);
			$this->child = $newChildList;
		}
	}

	function getChildArray($resumeArray) {
		if ($this->id == NULL) {
			$qry = "SELECT * FROM resumes WHERE resume_id = $this->resume_id AND id = 0";
			//Needs a select on where parent_id = NULL right there...
			$res = mysql_query($qry)
				or die("Unable to retrieve infos... (error.3)");

			$row = mysql_fetch_object($res);

			if (mysql_num_rows($res) != 0) {
				$this->depth 		= 0;
				$this->id 			= $row->id;
				$this->infos 		= $row->infos;
				$this->ordering 	= $row->ordering;
				$this->parent_id 	= $row->parent_id;
			} else {
				$this->depth 		= 0;
				$this->id 			= 0;
				$this->infos 		= "myResumeFromArray";
				$this->ordering 	= "";
				$this->parent_id 	= NULL;
			}
		}

		$i = 0;
		foreach ($resumeArray as $id => $value) {
			if($value["parent_id"] == $this->id) {
				//if ($value["infos"]!= "") { //What does it do without ?
					$myResume[$i] = new Resume($this->resume_id, $id, $value["parent_id"], $value["depth"], $value["ordering"], $value["infos"]);
					$myResume[$i]->getChildArray($resumeArray);
					$i++;
				//}
			}
		}
		$this->child = $myResume;
	}

	function getChildEmpty () {
	//We can do better !
		$MAX_LEVEL = 7; //Changed ?
		if ($this->depth == NULL) {
				$this->depth 		= 0;
				$this->id 			= 0;
				$this->infos 		= "myResumeFromEmpty";
				$this->ordering 	= "";
				$this->parent_id 	= NULL;
		}
		$level = $this->depth+1;
		if ($level <= $MAX_LEVEL) {
			$myResume[0] = new Resume($this->resume_id, $level, $level-1, $level, "", "Level $level");
			$myResume[0]->getChildEmpty();
			$level++;
		}
		$this->child = $myResume;
	}

	//Return the total number of sections and elements (in an array)
	function checkContent () {
		$n_section = 0;
		$n_element = 0;
		if (count($this->child) > 0) {
			foreach ($this->child as $key => $achild) {
				$n_element += count($achild->child);
				$n_section++;
			}
		}
		return array($n_section, $n_element);
	}

	function checkMinimumContent () {
	        $cv_mini_content = $this->checkContent();
		$enoughSections = TRUE;
		$enoughElements = TRUE;

        	if ($cv_mini_content[0] < 3) {
               	  //Pas assez de sections
		  $enoughSections = FALSE;
		}
               	if ($cv_mini_content[1] < 6) {
              	  //Assez de sections mais pas assez d'elements
		  $enoughElements = FALSE;
               	}
		return array ("sections" => $enoughSections, "elements" => $enoughElements);
	}


	function display() {

		$ordering = split (":",$this->ordering);
		//foreach ($ordering as $key => $value) {
			//echo "$value\n";
		//}

		$tmpResume = $this->child;

		$this->displayTags(0);
		foreach ($ordering as $key => $value) {
		  $i = 0;
		  while (isset($tmpResume[$i])) {
			  if ($value == $tmpResume[$i]->id) {
			    $tmpResume[$i]->display($tmpResume[$i]->infos);
			    $tmpResume[$i]->processed = TRUE;
				}
				$i++;
			}
		}

		$i = 0;
		while (isset($tmpResume[$i])) {
		  if ($tmpResume[$i]->processed != TRUE) {
			  $tmpResume[$i]->display($tmpResume[$i]->infos);
			}
			$i++;
		}
		$this->displayTags(1);
	}

	function sayNull($var) {
		if (is_null($var)) {
			return "NULL";
		} else {
			return $var;
		}
	}

	function insertObjects() {
		$qry_select = "SELECT * FROM resumes WHERE id = $this->id AND resume_id = $this->resume_id";
		$res_select = mysql_query($qry_select)
			or die("Unable to update...");

		if (mysql_num_rows($res_select) == 0) {
			$qry = "INSERT INTO resumes (resume_id, id, parent_id, infos, ordering)";
			$qry = $qry." VALUES ($this->resume_id,$this->id,".$this->sayNull($this->parent_id).",'".htmlspecialchars($this->infos)."','$this->ordering')";
		} else {
			$qry = "UPDATE resumes SET parent_id = ".$this->sayNull($this->parent_id).", infos = '".htmlspecialchars($this->infos)."', ordering = '$this->ordering' WHERE resume_id = $this->resume_id AND id = $this->id";
		}

		mysql_query($qry)
			or die("<br>Unable to update...");

		$i=0;
		$tmpResume = $this->child;
		while (isset($tmpResume[$i])) {
			$tmpResume[$i]->insertObjects();
			$i++;
		}
	}
	
	function displayModify($infos="") {

		$ordering = split (":",$this->ordering);
		foreach ($ordering as $key => $value) {
			//echo "$value\n";
		}

		$tmpResume = $this->child;

		$this->displayTagsModify(0);
		foreach ($ordering as $key => $value) {
		  $i = 0;
		  while (isset($tmpResume[$i])) {
			  if ($value == $tmpResume[$i]->id) {
			    $tmpResume[$i]->displayModify($tmpResume[$i]->infos);
			    $tmpResume[$i]->processed = TRUE;
				}
				$i++;
			}
		}

		$i = 0;
		while (isset($tmpResume[$i])) {
		  if ($tmpResume[$i]->processed != TRUE) {
			  $tmpResume[$i]->displayModify($tmpResume[$i]->infos);
			}
			$i++;
		}
		$this->displayTagsModify(1);
	}
	function displayData ($id) {;
		$tmpResume = $this->child;
		if($this->id == $id) {
			$data->depth		= $this->depth;
			$data->id			= $this->id;
			$data->infos		= $this->infos;
			$data->ordering	= $this->ordering;
			$data->parent_id	= $this->parent_id;
			$i = 0;
			if (isset($this->child)) {
				foreach($this->child as $key => $value) {
					$data->child_id[$i] = $value->id;
					$i++;
				}
			}
			return $data;
		} else {
			$data = NULL;
  	  		$i = 0;
			while (isset($tmpResume[$i]) && $data == NULL) {
				$data = $tmpResume[$i]->displayData ($id);
				$i++;
		 	}
		}
		return $data;
	}


	function insertElementLevel ($parentId, $infos) {

		$cvSelectMaxElementIdReq = "SELECT max(id) as max_id FROM netcv_resumes WHERE resume_id = '".$this->resume_id."'";
		$cvSelectMaxElementIdRes = $this->db->prepare($cvSelectMaxElementIdReq);
		$cvSelectMaxElementIdRes->execute();
        
		$cvMaxElementId = $cvSelectMaxElementIdRes->fetchAll();
		$newId = $cvMaxElementId[0]["max_id"] + 1;

		$cvInsertElementReq = "
            INSERT INTO netcv_resumes
                    (resume_id, id, parent_id, infos, ordering, date_creation, date_modification)
            VALUES  ('".$this->resume_id."','".$newId."','".$parentId."','".$infos."','', '".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";


		try
		{
		$this->db->exec($cvInsertElementReq);
		//$cvInsertElementRes = $this->db->prepare($cvInsertElementReq);
		//$cvInsertElementRes->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

		return $newId;
	}

	function updateElementOrdering ($id, $ordering) {
		$cvContentUpdateReq = "UPDATE netcv_resumes SET ordering = '".$ordering."' WHERE resume_id = '".$this->resume_id."' AND id = '".$id."'";
		try
		{
			$this->db->exec($cvContentUpdateReq);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

	}

	function updateElementInfos ($id, $infos/*, $ordering*/) {
		$cvContentUpdateReq = "UPDATE netcv_resumes SET infos = '".$infos."', date_modification = '".date("Y-m-d H:i:s")."' WHERE resume_id = '$this->resume_id' AND id = '$id'";
		try
		{
			$this->db->exec($cvContentUpdateReq);
			//$cvContentUpdateRes->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}

	function moveElement ($id, $direction) {
		$data_child = $this->displayData($id);

		$data_parent = $this->displayData($data_child->parent_id);

		$childIDs = $this->returnChildIDs ($data_child->parent_id);

		//Reload the ordering (in order to delete non existent IDs)
		$ordering = split (":",$data_parent->ordering);
		$newOrdering = array();
		foreach ($ordering as $key => $value) {
			  if (in_array ($value,$childIDs)) {
					array_push($newOrdering,$value);
				}
			}

			//Inserting missing IDs at the end of the array
			$missingID = array_diff($childIDs,$newOrdering);
			foreach ($missingID as $key => $value) {
					array_push($newOrdering,$value);
			}

			//Moving the ID (up or down)
			$id_pos = array_search($id,$newOrdering);
			if ($direction == "up") {
			  if (isset($newOrdering[$id_pos-1])) {
				  $tempValue = $newOrdering[$id_pos-1];
					$newOrdering[$id_pos-1] = $id;
					$newOrdering[$id_pos] = $tempValue;
				}
			} elseif ($direction == "down") {
			  if (isset($newOrdering[$id_pos+1])) {
				  $tempValue = $newOrdering[$id_pos+1];
					$newOrdering[$id_pos+1] = $id;
					$newOrdering[$id_pos] = $tempValue;
				}
			}
			$newOrderingString = implode(":", $newOrdering);
			$this->updateElementOrdering ($data_parent->id,$newOrderingString);

	}

	//USED for moveElement
	function returnChildIDs ($parent_id) {
		$cvContentSelectReq = "SELECT id FROM netcv_resumes WHERE resume_id = '$this->resume_id' AND parent_id = '$parent_id'";
		$cvContentSelectRes = $this->db->prepare($cvContentSelectReq);
		$cvContentSelectRes->execute();
		$cvChild = $cvContentSelectRes->fetchAll();

		$i = 0;
		if (count($cvChild) > 0) {
				foreach($cvChild as $cvSon) {
					$childIDs[$i] = $cvSon["id"];
					$i++;
				}
		}
		  
		return $childIDs;
	}


	function recurseDeleteElement ($id) {
        //Selection des fils
		$cvContentSelectReq = "SELECT * FROM netcv_resumes WHERE resume_id = '".$this->resume_id."' AND parent_id = $id";
		$cvContentSelectRes = $this->db->prepare($cvContentSelectReq);
		$cvContentSelectRes->execute();
		$cvChild = $cvContentSelectRes->fetchAll();

        //Suppression du pere
		$cvContentDeleteReq = "DELETE FROM netcv_resumes WHERE resume_id = ".$this->resume_id." AND id = $id";
        $cvContentDeleteRes = $this->db->prepare($cvContentDeleteReq);
        $cvContentDeleteRes->execute();
        
        //Suppression des fils
        if (count($cvChild) > 0) {
            foreach($cvChild as $cvSon) {
              $this->recurseDeleteElement ($cvSon["id"]);
            }
        }
	}

}

?>
