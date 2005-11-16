<?php

class NetCVUser {
		//var $id;
		var $authentified;
		var $infos; //Variable holding the user infos
		var $user;
		var $db;
		var $cvGroupList;
		var $cvContent;
		var $currentCVId; //Variable determinant le CV en cours
		var $personalInfo; //Tableau de tableaux contenant les infos personnelles de chaque CV
		
		function getCVGroupList () {
			$this->cvGroupList = new NetCVGroupList ($this->db, $this->getInfo("id"));
		}
        
		function returnCVGroupList() {
			$this->getCVGroupList();
			return $this->cvGroupList;
		}

		function getCVContent($cvId) {
			$this->cvContent[$cvId] = new NetCVContent($this->db, $cvId);
			$this->cvContent[$cvId]->getChildSQL();
		}
		function returnCVContent($cvId) {
			$this->getCVContent($cvId);
			return $this->cvContent[$cvId];
		}
		
		//Fonction retournant les informations personnelles du CV en inserant celle par defaut si des champs sont vides
		function returnPersonalInfo($cvId)
		{
			if (!isset($this->personalInfo[$cvId]))
			{
				$userInfoSelectReq = "SELECT u.* FROM netcv_users u WHERE id = '".$this->getInfo("id")."';";
				$userInfoSelectRes = $this->db->prepare($userInfoSelectReq);
				$userInfoSelectRes->execute();
				$userInfoSelectRow = $userInfoSelectRes->fetchAll(PDO::FETCH_ASSOC);
	
				$cvUserInfoSelectReq = "SELECT c.* FROM netcv_resumes_by_lang c WHERE id = '".$cvId."';";
				$cvUserInfoSelectRes = $this->db->prepare($cvUserInfoSelectReq);
				$cvUserInfoSelectRes->execute();
				$cvUserInfoSelectRow = $cvUserInfoSelectRes->fetchAll(PDO::FETCH_ASSOC);

	         $personalInfoArray = array();
				if (count($cvUserInfoSelectRow)>0)
				{
	                //Utilisation de la valeur par defaut si la valeur du SingleCV est NULL
					foreach($cvUserInfoSelectRow[0] as $key => $value)
					{
						if ($value==NULL)
						{
								$personalInfoArray[$key] = $userInfoSelectRow[0][$key];
	      			} else {
								$personalInfoArray[$key] = $value;
						}
					}
				}
				$this->personalInfo[$cvId] = $personalInfoArray;
				return $personalInfoArray;
			}
			else
			{
				return $this->personalInfo[$cvId];
			}
		}
		
		function countPersonalInfo ($cvid = FALSE)
		{
			//On va compter les infos perso par défaut
			if ($cvid == FALSE)
			{
				$total = 0;
				foreach($this->infos as $info)
				{
					if ($info != "")
					{
						$total += 1;
					}
				}
			}
			else
			{
				if (!isset($this->personalInfo[$cvid]))
				{
					$this->returnPersonalInfo($cvid);
				}
				
				$total = 0;
				foreach($this->personalInfo[$cvid] as $info)
				{
					if ($info != "")
					{
						$total += 1;
					}
				}
			}
			return $total;
		}

		function returnSingleCV($groupid, $cvid)
		{
				$singleCV = new NetCVSingleCV($this->db);
				$singleCV->load($this->getInfo("id"), $groupid, $cvid);
				return $singleCV;
		}
		
		//Ajout d'une version de CV (SingleCV)
		function createSingleCV($groupid, $cvInfos)
		{
			//Selection de l'ID maximum... Si vous avez une meilleure methode pour l'inserer dans singleCVInsertReq
			//... je suis preneur
			//Attention: probleme avec cette requete, si une table est vide, les deux valeurs passent a null!
			$maxSelectReq = "SELECT max(r.`resume_id`) as rmax, max(c.`id`) as cmax FROM netcv_resumes r, netcv_resumes_by_lang c";
			$maxSelectRes = $this->db->prepare($maxSelectReq);
			$maxSelectRes->execute();
			$maxSelectRow = $maxSelectRes->fetchAll(PDO::FETCH_ASSOC);
			$maxid = max ($maxSelectRow[0]["rmax"],$maxSelectRow[0]["cmax"])+1;
			//Glut pour éviter le problème de la variable NULL... (ne doit arriver qu'une seule fois.)
			if ($maxid == NULL) {
				$maxid = 100;
			}
						
			//Insertion d'un nouveau CV
			$singleCVInsertReq = "
				INSERT INTO netcv_resumes_by_lang
				(`id`, `group_id`,`lang`, `date_creation`, `date_modification`)
				VALUES ('".$maxid."', '".$groupid."', '".$cvInfos["lang"]."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
			$singleCVInsertRes = $this->db->prepare($singleCVInsertReq);
			$singleCVInsertRes->execute();
			
			$cvid = $this->db->lastInsertId();
			
			//Insertion du premier element (permettant le tri)
			$cvInsertElementReq = "
			INSERT INTO netcv_resumes
			  (resume_id, id, parent_id, infos, ordering, date_creation, date_modification)
			VALUES  ('$maxid',0,0,'','', '".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		
			$cvInsertElementRes = $this->db->prepare($cvInsertElementReq);
			$cvInsertElementRes->execute();
			
			return $cvid;
			
		}

		//Mise a jour d'une version de CV (SingleCV)
		function updateSingleCV($groupid, $cvid, $cvInfos) {
			$singleCVUpdateReq = "
				UPDATE netcv_resumes_by_lang c, netcv_resumes_group g
				SET c.`lang` = '".$cvInfos["lang"]."',
				c.`date_modification` = '".date("Y-m-d H:i:s")."'
				WHERE c.`group_id` = '".$groupid."'
				AND c.`id` = '".$cvid."'
				";
			$singleCVUpdateRes = $this->db->prepare($singleCVUpdateReq);
			$singleCVUpdateRes->execute();
			
		}
		//Suppression d'une version de CV (SingleCV)
		//Cela ne supprime pas le contenu
		function deleteSingleCV($groupid, $cvid) {
/*
			$singleCVDeleteReq = "
				DELETE FROM netcv_resumes_by_lang
				WHERE `group_id` = '".$groupid."'
				AND `id` = '".$cvid."';
				";
*/
			$singleCVSelectReq = "
				SELECT * FROM netcv_resumes_by_lang
				WHERE `group_id` IN (SELECT id FROM netcv_resumes_group WHERE id = '".$groupid."' AND user_id = '".$this->getInfo("id")."')
				AND `id` IN (SELECT id FROM netcv_resumes_by_lang WHERE group_id = '".$groupid."' AND id = '".$cvid."');
				";
			$singleCVSelectRes = $this->db->prepare($singleCVSelectReq);
			$singleCVSelectRes->execute();
			
			if ($singleCVSelectRows = $singleCVSelectRes->fetchAll(PDO::FETCH_ASSOC)) {
				$singleCVDeleteReq = "
					DELETE FROM netcv_resumes_by_lang
					WHERE `group_id` = '".$singleCVSelectRows[0]['group_id']."'					AND `id` = '".$singleCVSelectRows[0]['id']."'
					";
				$singleCVDeleteRes = $this->db->prepare($singleCVDeleteReq);
				$singleCVDeleteRes->execute();
				
				$singleCVContentDeleteReq = "
					DELETE FROM netcv_resumes
					WHERE `resume_id` = '".$singleCVSelectRows[0]['id']."'
					";
				$singleCVContentDeleteRes = $this->db->prepare($singleCVContentDeleteReq);
				$singleCVContentDeleteRes->execute();
			}
			
		}

		function __construct($db, $user, $readonly = FALSE) {

			$this->user = $user;
			$this->db = $db;
			
			//Initialisation de la propriete currentCVId
			$this->currentCVId = NULL;

			if ( ($this->user) ) {
				if (is_object($this->user)) {
					//Selection de l'utilisateur NetCV via le login Karibou
					$userSelectReq = "SELECT * FROM netcv_users WHERE username = '".$this->user->getLogin()."'";
				} elseif (is_string($this->user) || is_int($this->user) ) {
					$userSelectReq = "SELECT * FROM netcv_users WHERE id = '".$this->user."'";
				}
				$userSelectRes = $this->db->prepare($userSelectReq);
				$userSelectRes->execute();

				$this->authentified = FALSE;

				//Teste si l'enregistrement existe
				if ( $userSelectRow = $userSelectRes->fetchAll(PDO::FETCH_ASSOC) ) {
						$this->authentified = TRUE;
						$this->infos = $userSelectRow[0];
						//Verification du remplissage du CV
						$this->updateResumeCompletion ();
				} elseif (!$readonly) {
					//Les informations ne sont pas presentes dans la base on les créé si on n'est pas en mode visualisation
					$this->createUser();
				}
			}
		}
		
		//Retourne l'info demandee
		function getInfo ($key) {
			if (isset($this->infos[$key])) {
				return $this->infos[$key];
			} else {
				return FALSE;
			}
		}
		//Retourne la preference demandee
		function getPref ($key) {
			if (isset($this->prefs->$key)) {
				return $this->prefs->$key;
			} else {
				return FALSE;
			}
		}
		
		function createUser() {
			$userInsertReq = "
				INSERT INTO netcv_users (username, firstname, lastname, email,date_creation, date_modification)
				VALUES ('".$this->user->getLogin()."','".mysql_escape_string($this->user->getFirstname())."','".mysql_escape_string($this->user->getLastname())."','".mysql_escape_string($this->user->getEmail())."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
			$userInsertRes = $this->db->prepare($userInsertReq);
			$userInsertRes->execute();

			return TRUE;
		}

		//Creation des enregistrements dans la base de donnees (preferences, resumes)
		//et retour du nouvel ID de CV
		function createResumeID() {
			//Recuperation de l'ID maximum present dans la base
			$maxResumeIDSelectReq = "
				SELECT max( netcv_resumes.resume_id ) AS maxr, max( netcv_resumes_stats.resume_id ) AS maxrs, max( netcv_users.resumes ) AS maxu
				FROM `netcv_resumes`, `netcv_resumes_stats`, `netcv_users`";
			$maxResumeIDSelectRes = $this->db->prepare($maxResumeIDSelectReq);
			$maxResumeIDSelectRes->execute();

			if ($maxResumeIDSelectRow = $maxResumeIDSelectRes->fetchAll(PDO::FETCH_ASSOC)) {
				$maxResumeID = max($maxResumeIDSelectRow[0]["maxrs"], $maxResumeIDSelectRow[0]["maxr"], $maxResumeIDSelectRow[0]["maxu"]);
			} else {
				$maxResumeID = 0;
			}

			//Incrementation pour le nouvel identifiant
			$maxResumeID++;

			//Insert l'enregistrement racine
			$maxResumeIDInsertReq = "
				INSERT INTO netcv_resumes
				(resume_id, id, parent_id, infos, ordering, date_	creation, date_modification)
				VALUES ('$maxResumeID', 0, NULL, '', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
			$maxResumeIDInsertRes = $this->db->prepare($maxResumeIDInsertReq);
			$maxResumeIDInsertRes->execute();


			//Insert l'enregistrement des preferences
			$maxResumeIDInsertPrefsReq = "
				INSERT INTO netcv_preferences (resume_id, sendStatsDelay, sendStatsDate, skin, cvHost)
				VALUES ('$maxResumeID','90', '".date("Y-m-d H:i:s")."', '0', '".$this->user->getLogin()."')";
			$maxResumeIDInsertPrefsRes = $this->db->prepare($maxResumeIDInsertPrefsReq);
			$maxResumeIDInsertPrefsRes->execute();
			
			//Retourne l'identifiant utilise
			return $maxResumeID;
		}

		function visitors() {
		  $visitors = 0;
		  if($this->getInfo("resumes") != "") {

				$qry = "SELECT count(*) as visitors FROM resumes_stats WHERE resume_id = '".$this->getInfo("resumes")."'";
				$res = mysql_query($qry)
					or die("Query problem...");

				$row = mysql_fetch_object($res);

				$visitors = $row->visitors;
		  }
		  return $visitors;
		}

		//Last visit in second
		function last_visit() {

			  $last_visit = 0;
			  if($this->getInfo("resumes") != "") {
					//Derniere visite sur les CVs

					$cv_last_visit_qry = "SELECT max(datetime) as max_date FROM resumes_stats WHERE resume_id = '".$this->getInfo("resumes")."'";
					$cv_last_visit_res = mysql_query ($cv_last_visit_qry);

			if (mysql_num_rows($cv_last_visit_res) > 0) {
			  $cv_last_visit_obj = mysql_fetch_object($cv_last_visit_res);
					  $last_visit = $cv_last_visit_obj->max_date;
					  $last_visit = datediff('s', $last_visit, date("Y-m-d H:i:s"));
			} else {
			  return FALSE;
			}
			   }
			   return $last_visit;
		}



		//Verifie le remplissage du CV par rapport aux donnees SQL
		function enoughContent($minInfos = MIN_INFOS, $minSections = MIN_SECTIONS, $minElements = MIN_ELEMENTS) {
		  if (   ($this->prefs->enoughInfos < $minInfos)
			  || ($this->prefs->enoughSections < $minSections)
			  || ($this->prefs->enoughElements < $minElements)  )
		  {
			return FALSE;
		  } else {
			return TRUE;
		  }
		}

		//Teste si le CV est assez rempli pour pouvoir etre publie
		//et met a jour les preferences
		function updateResumeCompletion () {
		  if (is_numeric($this->getInfo("resumes"))) {
			$myResumeTemp = new NetCVContent($this->db, $this->getInfo("resumes"));
			$myResumeTemp->getChildSQL();
			$checkContent = $myResumeTemp->checkContent(); //Array (nsections, nelements)
			$checkInfos = $this->updateInfoCompletion(); //Int

			//Mise a jour des preferences
			$prefsUpdateReq = "
				UPDATE netcv_preferences
				SET enoughInfos = '".$checkInfos."', enoughSections = '".$checkContent[0]."', enoughElements = '".$checkContent[1]."'
				WHERE resume_id = '".$this->getInfo("resumes")."'";
			$prefsUpdateRes = $this->db->prepare($prefsUpdateReq);
			$prefsUpdateRes->execute();

			$this->prefs->enoughInfos	 = $checkInfos;
			$this->prefs->enoughSections  = $checkContent[0];
			$this->prefs->enoughElements  = $checkContent[1];
		  }
		}

		//Teste si les informations utilisateur sont assez remplies
		function updateInfoCompletion () {
			  $user_infos_number =
					empty01($this->getInfo("address"))
					+ empty01($this->getInfo("firstname"))
					+ empty01($this->getInfo("lastname"))
					+ empty01($this->getInfo("phone"))
					+ empty01($this->getInfo("email"))
					+ empty01($this->getInfo("phone"))
					+ empty01($this->getInfo("other_infos"))
					+ empty01($this->getInfo("job_title"));

		  return $user_infos_number;
		}

		function delResume() {
			$qry_del = "DELETE FROM resumes WHERE resume_id = '".$this->getInfo("resumes")."'";
			$res_del = mysql_query($qry_del)
				or die ("Unable to delete(1)");

			$qry_update = "UPDATE users SET resumes = '' WHERE username = '".$this->getInfo("username")."'";
			$res_update = mysql_query($qry_update)
				or die ("Unable to delete(2)");

			return TRUE;
		}

		function refresh() {
				$qry = "SELECT * FROM netcv_users WHERE username = '".$this->username."'";
				$res = mysql_query($qry)
					or die("Query problem...");

				$num_rows = mysql_num_rows($res);
				if ($num_rows > 0) {
					//User exists
					$this->infos = mysql_fetch_object($res);
				} else {
					//User does NOT exist
					$this->infos = FALSE;
				}


				$qry_stats = "SELECT * FROM netcv_preferences WHERE resume_id = '".$this->getInfo("resumes")."'";
				$res_stats = mysql_query($qry_stats)
					or die("Query problem...");

				$num_rows_stats = mysql_num_rows($res_stats);
				if ($num_rows_stats > 0){
					$this->prefs = mysql_fetch_object($res_stats);
				}
		}
}

function empty01($theVar) {
			if (empty($theVar)) {
				return 0;
			} else {
				return 1;
			}
}

?>