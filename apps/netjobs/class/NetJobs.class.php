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
					last = '1'
				ORDER BY
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
	
	/* JOBS */
	public function getJobList()
	{
		$jobs = array();
	
		$sql = "
				SELECT *
				FROM netjobs_jobs
				WHERE
					last = '1'
				ORDER BY
					datetime
					DESC
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
	
	public function getJobById($jobid)
	{
		$sql = "
				SELECT *
				FROM netjobs_jobs
				WHERE
					id = '$jobid'
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
				
				$currentUser = $this->userFactory->getCurrentUser();
				
				$sqlc = "
						INSERT INTO netjobs_jobs
						(id, last, user_id, title, description, type, salary, company_id, datetime)
						VALUES
						('".$jobinfos["id"]."', 1, '".$currentUser->getId()."','".$jobinfos["title"]."', '".$jobinfos["description"]."', '".$jobinfos["type"]."', '".$jobinfos["salary"]."','".$jobinfos["company_id"]."', NOW())
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
				unset($stmtm);
				
				$currentUser = $this->userFactory->getCurrentUser();
				
				$sqlc = "
						INSERT INTO netjobs_jobs
						(id, last, user_id, title, description, type, salary, company_id, datetime)
						VALUES
						('".$maxjobid[0]["maxjobid"]."', 1, '".$currentUser->getId()."','".$jobinfos["title"]."', '".$jobinfos["description"]."', '".$jobinfos["type"]."', '".$jobinfos["salary"]."','".$jobinfos["company_id"]."', NOW())
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
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}

	}
	
}

?>