<?php

class NetCVDB {

	protected $db;
	protected $cvlist;
	
	function __construct(PDO $db)
	{
		$this->db = $db;
	}
	
	function getAllCV ($diffusion = FALSE)
	{
				if ($diffusion === FALSE)
					$diffusion = '';
				else
					$diffusion = 'AND netcv_resumes_group.diffusion = \''.$diffusion.'\'';
				
				$allcvReq = "
				SELECT
					netcv_resumes_group.id, netcv_resumes_group.user_id, netcv_resumes_group.hostname, netcv_resumes_group.diffusion,
					netcv_resumes_by_lang.id, netcv_resumes_by_lang.lang, netcv_resumes_by_lang.group_id, netcv_resumes_by_lang.jobtitle as langjobtitle,
					netcv_users.username, netcv_users.firstname, netcv_users.lastname, netcv_users.jobtitle,
					TIMESTAMP(MAX(netcv_resumes.date_modification)) as last_modification
				FROM
					netcv_resumes_group, netcv_resumes_by_lang, netcv_users, netcv_resumes
				WHERE 
						netcv_resumes_group.id = netcv_resumes_by_lang.group_id
					AND
						netcv_users.id = netcv_resumes_group.user_id
					AND
						netcv_resumes.resume_id = netcv_resumes_by_lang.id
					$diffusion
				GROUP BY
					netcv_resumes.resume_id
				ORDER BY
					username";
				
				/* LEFT JOIN netcv_skins ON netcv_skins.id = netcv_resumes_group.skin_id */
				$allcvRes = $this->db->prepare($allcvReq);
				$allcvRes->execute();

				if ( $allcvRows = $allcvRes->fetchAll(PDO::FETCH_ASSOC))
				{
					$this->cvlist = $allcvRows;
				}
				else
				{
					$this->cvlist = FALSE;
				}
				return $this->cvlist;
	}
}
?>