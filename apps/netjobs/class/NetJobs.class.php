<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
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
class NetJobs
{
	protected $db;
	protected $userFactory;
	protected $profileFactory;
	
	protected $locationInfosSQLSelect;

	public function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
		
		$this->locationInfosSQLSelect = "
							netjobs_locations.type				AS locationinfos_type,
							netjobs_locations.country_id		AS locationinfos_country_id,
							netjobs_locations.county_id		AS locationinfos_county_id,
							netjobs_locations.department_id	AS locationinfos_department_id,
							netjobs_locations.city_id			AS locationinfos_city_id,
							netjobs_locations.country_name	AS locationinfos_country_name,
							netjobs_locations.county_name		AS locationinfos_county_name,
							netjobs_locations.department_name	AS locationinfos_department_name,
							netjobs_locations.city_name		AS locationinfos_city_name
							";
	}
	
	/* COMPANIES */
	public function getCompanyList()
	{
		$companies = array();
	
		$sql = "
				SELECT	netjobs_companies.*, UNIX_TIMESTAMP(netjobs_companies.datetime) AS timestamp,
							".$this->locationInfosSQLSelect."
				FROM netjobs_companies
				LEFT OUTER JOIN netjobs_locations 
					ON (netjobs_companies.id = netjobs_locations.id) && (netjobs_locations.type = 'company')
				WHERE
					netjobs_companies.last = 1
				AND
					netjobs_companies.deleted = 0
				ORDER BY
					netjobs_companies.datetime
					DESC,
					netjobs_companies.name
					ASC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$companiesinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($companiesinfos)>0)
			{
				foreach ($companiesinfos as $companyinfos)
				{
				
					$companies[] = new NJCompany($companyinfos,$this->userFactory);
				}
			}
			else
			{
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return $companies;
	}
	
	public function getCompanyById($companyid)
	{
		$sql = "
				SELECT	netjobs_companies.*, UNIX_TIMESTAMP(netjobs_companies.datetime) AS timestamp,
							COUNT(netjobs_jobs.id) as joboffers,
							".$this->locationInfosSQLSelect."
				FROM netjobs_companies
				LEFT OUTER JOIN netjobs_jobs
					ON (netjobs_companies.id = netjobs_jobs.company_id) && (netjobs_jobs.deleted = 0) && (netjobs_jobs.last = 1)
				LEFT OUTER JOIN netjobs_locations 
					ON (netjobs_companies.id = netjobs_locations.id) && (netjobs_locations.type = 'company')
				WHERE
					netjobs_companies.id = '$companyid'
				AND
					netjobs_companies.last = 1
				GROUP BY
					netjobs_companies.id
				ORDER BY
					netjobs_companies.datetime
					DESC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$companyinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			if (count($companyinfos)>0)
			{
				$company = new NJCompany($companyinfos[0],$this->userFactory);
				/*
				$sqlc = "
						SELECT *
						FROM netjobs_companies
						WHERE
							id = '".$company->getInfo("company_id")."'
						AND
							last = 1
					";
				*/
				return $company;
			}
			else
			{
				return FALSE;
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return FALSE;
	}
	
	public function saveCompany ($companyinfos)
	{
		
		if (isset($companyinfos["id"]))
		{
			$sqlu = "
					UPDATE netjobs_companies
					SET last = '0'
					WHERE	id = '".$companyinfos["id"]."'
				";
			try
			{
				$stmtu = $this->db->exec($sqlu);
				unset($stmtu);
				$companyid = $companyinfos["id"];
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			$sqlm = "
					SELECT MAX(id) as maxcompanyid
					FROM netjobs_companies
				";
				
			try
			{
				$stmtm = $this->db->query($sqlm);
				$maxcompanyid = $stmtm->fetchAll(PDO::FETCH_ASSOC);
				$companyid = $maxcompanyid[0]["maxcompanyid"] + 1;
				unset($stmtm);
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		
		if (isset($companyid))
		{
		
			$currentUser = $this->userFactory->getCurrentUser();
			
			$sqlc = "
					INSERT INTO netjobs_companies
					(id, last, user_id, name, description, type, datetime)
					VALUES
					('".$companyid."', 1, '".$currentUser->getId()."','".$companyinfos["name"]."', '".$companyinfos["description"]."', '".$companyinfos["type"]."', NOW())
				";
					
			try
			{
				$stmtc = $this->db->exec($sqlc);
				unset($stmtc);
				return $companyid;
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function deleteCompany ($companyid)
	{
		

		$sqlu = "
				UPDATE netjobs_companies
				SET deleted = '1', datetime = NOW()
				WHERE
					id = '".$companyid."'
				AND
					last = 1
			";
			
		try
		{
			$stmtu = $this->db->exec($sqlu);
			unset($stmtu);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
	}
	
	/* JOBS */
	public function getJobList($max = FALSE, $page = FALSE, $companyid = FALSE)
	{
		$jobs = array();
		
		//Need a page ?
		if ($page === FALSE)
		{
			$page = 1;
		}

		//Has a max number of lines to display ?
		if ($max !== FALSE)
		{
			if ($page === FALSE)
			{
				$page = 1;
			}
			$limit = "LIMIT ".($page - 1) * $max.", $max";
		}
		else
		{
			$limit = "";
		}
		
		//Return jobs posted by a specific company ?
		if ($companyid !== FALSE)
		{
			$companySQLSelect = "netjobs_companies.id as companyid,";
			$companySQLCondition = "
				AND netjobs_companies.id = netjobs_jobs.company_id
				AND netjobs_companies.id = $companyid
				AND netjobs_companies.last = 1
				AND netjobs_companies.deleted = 0";
			$companySQLFrom = ", netjobs_companies";
		}
		else
		{
			$companySQLSelect = $companySQLCondition = $companySQLFrom = "";
		}
	
		$sql = "
				SELECT
					netjobs_jobs.*, UNIX_TIMESTAMP(netjobs_jobs.datetime) AS timestamp, $companySQLSelect
					".$this->locationInfosSQLSelect."
				FROM
					netjobs_jobs $companySQLFrom
				LEFT OUTER JOIN
					netjobs_locations 
					ON (netjobs_jobs.id = netjobs_locations.id) && (netjobs_locations.type = 'job')
				WHERE
					netjobs_jobs.last = '1'
				AND
					netjobs_jobs.deleted = 0
				$companySQLCondition
				ORDER BY
					netjobs_jobs.datetime
					DESC
				$limit
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$jobsinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($jobsinfos)>0)
			{
				$companiesidSQL = "";
				foreach ($jobsinfos as $jobinfos)
				{
					$jobs[] = new NJJob($jobinfos,$this->userFactory);
					if ($companiesidSQL == "")
					{
						$companiesidSQL .= "id = ".$jobinfos["company_id"];
					}
					else
					{
						$companiesidSQL .= " OR id = ".$jobinfos["company_id"];
					}
				}
				
				//Fetch all displayed companies infos
				$sqlc = "
						SELECT *
						FROM netjobs_companies
						WHERE
							$companiesidSQL
					";
						
				try
				{
					$stmtc = $this->db->query($sqlc);
					$companiesinfos = array();
					while ($companyinfos = $stmtc->fetch(PDO::FETCH_ASSOC))
					{
						$companiesinfos[$companyinfos["id"]] = $companyinfos;
					}
					unset($stmtc);

					foreach ($jobs as $job)
					{
						if (isset($companiesinfos[$job->getInfo("company_id")]))
						{
							$job->company = new NJCompany($companiesinfos[$job->getInfo("company_id")],$this->userFactory);;
						}
						else
						{
							$job->company = FALSE;
						}						
					}
					
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
				
			}
			else
			{
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return $jobs;
	}
	
	public function countJobs()
	{
		$sql = "
				SELECT 
					COUNT(id) AS jobcount
				FROM
					netjobs_jobs
				WHERE
					last = '1'
				AND
					deleted = 0
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$jobcount = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			return $jobcount[0]["jobcount"];
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	
	public function getJobById($jobid)
	{
		$sql = "
				SELECT 	netjobs_jobs.*, UNIX_TIMESTAMP(netjobs_jobs.datetime) AS timestamp, netjobs_contacts.contact_id as contactid,
							".$this->locationInfosSQLSelect."
				FROM		netjobs_jobs
				LEFT OUTER JOIN netjobs_locations 
					ON (netjobs_jobs.id = netjobs_locations.id) && (netjobs_locations.type = 'job')
				LEFT OUTER JOIN netjobs_contacts
					ON (netjobs_jobs.id = netjobs_contacts.id) && (netjobs_contacts.type = 'job')
				WHERE
					netjobs_jobs.id = '$jobid'
				AND
					netjobs_jobs.last = 1
				ORDER BY
					netjobs_jobs.datetime
					DESC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$jobinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			if (count($jobinfos)>0)
			{
				$job = new NJJob($jobinfos[0],$this->userFactory);
				
				$sqlc = "
						SELECT 	netjobs_companies.*, UNIX_TIMESTAMP(netjobs_companies.datetime) AS timestamp,
									".$this->locationInfosSQLSelect."
						FROM	netjobs_companies
						LEFT OUTER JOIN netjobs_locations 
							ON (netjobs_companies.id = netjobs_locations.id) && (netjobs_locations.type = 'company')
						WHERE
							netjobs_companies.id = '".$job->getInfo("company_id")."'
						AND
							netjobs_companies.last = 1
					";
						
				try
				{
					$stmtc = $this->db->query($sqlc);
					$companyinfos = $stmtc->fetchAll(PDO::FETCH_ASSOC);
					unset($stmtc);
					if (count($companyinfos)>0)
					{
						$job->company = new NJCompany($companyinfos[0],$this->userFactory);
					}
					else
					{
						$job->company = FALSE;
					}
					
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
				
				return $job;
			}
			else
			{
				return FALSE;
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return FALSE;
	}
	
	public function saveJob ($jobinfos)
	{
		
		if (isset($jobinfos["id"]))
		{
			$sqlu = "
					UPDATE netjobs_jobs
					SET last = '0'
					WHERE	id = '".$jobinfos["id"]."'
				";
			try
			{
				$stmtu = $this->db->exec($sqlu);
				unset($stmtu);
				$jobid = $jobinfos["id"];
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			$sqlm = "
					SELECT MAX(id) as maxjobid
					FROM netjobs_jobs
				";
				
			try
			{
				$stmtm = $this->db->query($sqlm);
				$maxjobid = $stmtm->fetchAll(PDO::FETCH_ASSOC);
				$jobid = $maxjobid[0]["maxjobid"] + 1;
				unset($stmtm);
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		
		//If no company is assigned to this job, set company_id to 0
		if (!isset($jobinfos["company_id"]))
		{
			$jobinfos["company_id"] = 0;
		}
		
		if (isset($jobid))
		{
			$currentUser = $this->userFactory->getCurrentUser();
			
			$sqlc = "
					INSERT INTO netjobs_jobs
					(id, last, user_id, title, description, profile, type, education, salary, experience_required, company_id, datetime)
					VALUES
					('".$jobid."', 1, '".$currentUser->getId()."','".$jobinfos["title"]."', '".$jobinfos["description"]."', '".$jobinfos["profile"]."', '".$jobinfos["type"]."', '".$jobinfos["education"]."', '".$jobinfos["salary"]."', '".$jobinfos["experience_required"]."', '".$jobinfos["company_id"]."', NOW())
				";
					
			try
			{
				$stmtc = $this->db->exec($sqlc);
				unset($stmtc);
				return $jobid;
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function saveJobLocation ($jobid, $locationinfos)
	{
		
		$sql = "
				SELECT *
				FROM
					netjobs_locations
				WHERE
					`id` = '$jobid'
				AND `type` = 'job'
			";
		try
		{
			$stmt = $this->db->query($sql);
			$locationinfosSQL = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

		if (count($locationinfosSQL) == 0)
		{
			//insert
			$columns = "";
			$values = "";
			foreach ($locationinfos as $key => $value)
			{
				$columns	.= "`".$key."`, ";
				$values 	.= "'".$value."',";
			}
			

			$sqlc = "
					INSERT INTO netjobs_locations
					(`id`, `type`, $columns `datetime`)
					VALUES
					('".$jobid."', 'job', $values NOW())
				";
					
			try
			{
				$stmtc = $this->db->exec($sqlc);
				unset($stmtc);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			//update
			//insert
			$set = "";
			foreach ($locationinfos as $key => $value)
			{
				$set .= "`".$key."` = '".$value."',";
			}
			
			$sqlu = "
					UPDATE netjobs_locations
					SET
						$set
						datetime = NOW()
					WHERE
							`id` = $jobid
						AND
							`type` = 'job'
				";
					
			try
			{
				$stmtu = $this->db->exec($sqlu);
				unset($stmtu);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}

		return $jobid;
	}
	
	/**
	 * Saves contact choice in table netjobs_contacts
	 */
	public function saveContactChoice ($jobid, $companyid, $contactid)
	{
		//Deleting contacts of the job (this only permits one contact per job)
		$sqld = "
			DELETE FROM
				netjobs_contacts
			WHERE
				`id` = '$jobid'
			AND
				`type` = 'job'
				";
	
		try
		{
			$stmtd = $this->db->exec($sqld);
			unset($stmtd);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		//Choosing that contact for the job and the company
		$sqlc1 = "
				INSERT INTO netjobs_contacts
				(`contact_id`, `id`, `type`, `datetime`)
				VALUES
				('$contactid', '$jobid', 'job', NOW())
				ON DUPlICATE KEY
					UPDATE `datetime` = NOW(); 
			";
		$sqlc2 = "
				INSERT INTO netjobs_contacts
				(`contact_id`, `id`, `type`, `datetime`)
				VALUES
				('$contactid', '$companyid', 'company', NOW())
				ON DUPlICATE KEY
					UPDATE `datetime` = NOW(); 
			";
				
		try
		{
			$stmtc1 = $this->db->exec($sqlc1);
			$stmtc2 = $this->db->exec($sqlc2);
			unset($stmtc1);
			unset($stmtc2);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
			return FALSE;
		}
		return TRUE;
		
	}
	
	public function deleteJob ($jobid)
	{
		

		$sqlu = "
				UPDATE netjobs_jobs
				SET deleted = '1', datetime = NOW()
				WHERE
					id = '".$jobid."'
				AND
					last = 1
			";
			
		try
		{
			$stmtu = $this->db->exec($sqlu);
			unset($stmtu);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
	}
	
	/* Contacts */
	public function getContactListInCompany ($companyid)
	{
		if (!isset($this->profileFactory))
		{
			$this->profileFactory = new ProfileFactory($this->db, "addressbook");
		}
	
		//Selecting company contacts id
		$sql = "
				SELECT contact_id
				FROM
					netjobs_contacts
				WHERE
					`type` = 'company'
				AND
					`id` = $companyid
			";
		try
		{
			$stmt = $this->db->query($sql);
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

		$contactsids = array();
		if (count($results)>0)
		{
			foreach($results as $results)
			{
				$contactsids[] = $results["contact_id"];
			}
		}
		//Fetching contacts profiles
		$profiles = $this->profileFactory->fetchFromIds($contactsids);
		return $profiles;		
	}
	
	public function getContactById ($contactid)
	{
		if ($contactid != "" && $contactid > 0)
		{
			if (!isset($this->profileFactory))
			{
				$this->profileFactory = new ProfileFactory($this->db, "addressbook");
			}
			$profile = $this->profileFactory->fetchFromId($contactid);
			
			if ($profile !== FALSE)
			{
				$this->profileFactory->fetchAddresses($profile);
				$this->profileFactory->fetchPhones($profile);
				$this->profileFactory->fetchEmails($profile);
		
				return array (
							"profile"	=> $profile->getProfile(),
							"addresses"	=> $profile->getAddresses(),
							"phones" 	=> $profile->getPhones(),
							"emails"		=> $profile->getEmails()
								);
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
}

?>