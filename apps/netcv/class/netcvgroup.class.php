<?php
//Classe gerant les groupes de CV de l'utilisateur
class NetCVGroup {
    public $infos;
    public $CVList;

	//$infos peut être un string (hostname) ou un array (les infos du groupe)
	function __construct ($db, $infos) {
			$this->db = $db;
			if (is_array($infos)) {
				//Cas où les informations du groupe sont passé en paramètre
				$this->infos = $infos;
			} elseif (is_string($infos)) {
				//Cas où c'est le hostname qui est passé en paramètre
				$cvGroupSelectReq = "
				SELECT netcv_resumes_group.*, netcv_skins.filename as skin_filename FROM netcv_resumes_group
				LEFT JOIN netcv_skins ON netcv_skins.id = netcv_resumes_group.skin_id
				 WHERE netcv_resumes_group.hostname = '".$infos."'";
				$cvGroupSelectRes = $this->db->prepare($cvGroupSelectReq);
				$cvGroupSelectRes->execute();

				if ( $cvGroupSelectRows = $cvGroupSelectRes->fetchAll(PDO::FETCH_ASSOC))
				{
					$this->infos = $cvGroupSelectRows[0];
				}
				else
				{
				}

			} else {
			}
			$this->CVList = new NetCVSingleCVList($this->db, $this->getInfo("id"));
	}
    
    function getInfo ($key) {
        if (isset($this->infos[$key])) {
            return $this->infos[$key];
        } else {
          return FALSE;
        }
    }

    function returnCVList () {
        return $this->CVList->returnCVList();
    }
    
    function returnCVListObject () {
			return $this->CVList;
    }
 
}
?>
