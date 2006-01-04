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

	public function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
	}
	
	/* COMPANIES */
	public function getCompanyList()
	{
		$companies = array();
	
		$sql = "
				SELECT *
				FROM netjobs_companies
				WHERE
					last = 1
				AND
					deleted = 0
				ORDER BY
					datetime
					DESC,
					name
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
				SELECT netjobs_companies.*, COUNT(netjobs_jobs.id) as joboffers
				FROM netjobs_companies, netjobs_jobs
				WHERE
					netjobs_companies.id = '$companyid'
				AND
					netjobs_companies.last = 1
				AND
					netjobs_companies.id = netjobs_jobs.company_id
				AND
					netjobs_jobs.last = 1
				AND
					netjobs_jobs.deleted = 0
				GROUP BY
					netjobs_companies.id
				ORDER BY
					datetime
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
				
				$sqlc = "
						SELECT *
						FROM netjobs_companies
						WHERE
							id = '".$company->getInfo("company_id")."'
						AND
							last = 1
					";
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
				$maxjobid = $stmtm->fetchAll(PDO::FETCH_ASSOC);
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
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
	}
	
	/* JOBS */
	public function getJobList($maxjobs = FALSE, $page = FALSE)
	{
		$jobs = array();
	
		if ($maxjobs !== FALSE)
		{
			if ($page === FALSE)
			{
				$page = 0;
			}
			$limit = "LIMIT ".$page * $maxjobs.", $maxjobs";
		}
		else
		{
			$limit = "";
		}
	
		$sql = "
				SELECT *
				FROM netjobs_jobs
				WHERE
					last = '1'
				AND
					deleted = 0
				ORDER BY
					datetime
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
				foreach ($jobsinfos as $jobinfos)
				{
				
					$jobs[] = new NJJob($jobinfos,$this->userFactory);
					/*
					$sqlc = "
							SELECT *
							FROM netjobs_company
							WHERE	id = '".$job->getInfo("company_id")."'
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
					*/
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
				SELECT *
				FROM netjobs_jobs
				WHERE
					id = '$jobid'
				AND
					last = 1
				ORDER BY
					datetime
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
						SELECT *
						FROM netjobs_companies
						WHERE
							id = '".$job->getInfo("company_id")."'
						AND
							last = 1
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
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
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
	
}

?>