<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

/**
 * @package lib
 */
class Geo
{
	protected $db;
	protected $userFactory;
	protected $lang;
	
	protected $preparelist;

	public function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
		$this->prepare = array();
		$this->preparelist["countries"]		= array();
		$this->preparelist["counties"]		= array();
		$this->preparelist["department"]	= array();
		$this->preparelist["cities"]		= array();

		$currentUser = $this->userFactory->getCurrentUser();
		
		if ($currentUser->getPref("lang") != "")
		{
			$this->lang = $currentUser->getPref("lang");
		}
		else
		{
			$this->lang = "en";
		}
		
	}
	
	public function getCountryList()
	{
		$sql = "
				SELECT id, name_".$this->lang." as name
				FROM geo_countries
				ORDER BY name ASC
			";

		try
		{
				$stmt = $this->db->query($sql);
				$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return $countries;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	
	public function getCountyList($countryid)
	{
		$sql = "
				SELECT id, name
				FROM geo_counties_$countryid
				ORDER BY name ASC
			";

		try
		{
				$stmt = $this->db->query($sql);
				$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return $countries;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	
	public function getCityFromSearch($countryid, $search)
	{

	}
	
	/* Prepare functions */
	public function prepareFromId($location)
	{
		if (isset($location["city_id"]) && $location["city_id"] != "")
		{
			$this->preparelist["cities"][] = $location["city_id"];
		}
		
		if (isset($location["department_id"]) && $location["department_id"] != "")
		{
			$this->preparelist["departments"][] = $location["department_id"];
		}
		
		if (isset($location["county_id"]) && $location["county_id"] != "")
		{
			$this->preparelist["county"][] = $location["county_id"];
		}
		
		if (isset($location["country_id"]) && $location["country_id"] != "")
		{
			$this->preparelist["country"][] = $location["country_id"];
		}
	}
	
	public function getLocationString($location)
	{
		$locationString = "";
		//Select country info
		if (isset($location["country_id"]) && $location["country_id"] != "")
		{
			$sql = "
					SELECT
						name_".$this->lang." as name
					FROM
						geo_countries
					WHERE
						id = ".$location["country_id"]."				";
	
			try
			{
					$stmt = $this->db->query($sql);
					$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$country = $countries[0];
					$locationString .= $country["name"];
					unset($stmt);
			
					//Select county info (if applicable)				
					if (isset($location["county_id"]) && $location["county_id"] != "")
					{
						$sql = "
								SELECT
									name
								FROM
									geo_counties_".$location["country_id"]."
								WHERE
									id = ".$location["county_id"]."							";
				
						try
						{
								$stmt = $this->db->query($sql);
								$counties = $stmt->fetchAll(PDO::FETCH_ASSOC);
								$county = $counties[0];
								$locationString .= " &gt; ".$county["name"];
								unset($stmt);
						}
						catch(PDOException $e)
						{
							Debug::kill($e->getMessage());
						}
					}
					else
					{
						Debug::display("Geo : No county_id");
					}
					
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			Debug::display("Geo : No country_id");
		}


		if (
			isset($location["country_name"])
			&& 
			$location["country_name"] != ""
			&& 
				(
				 (!isset($location["country_id"]) 
				 ||
				  ($location["country_id"] == ""))
				  ))
		{
			$locationString .= $location["country_name"];
		}
		
		if (isset($location["county_name"]) && $location["county_name"] != "" && (!isset($location["county_id"])||($location["county_id"] == "")||($location["county_id"] == "0")))
		{
			$locationString .= " &gt; " . $location["county_name"];
		}
		
		if (isset($location["department_name"]) && $location["department_name"] != "" && (!isset($location["department_id"]) || ($location["department_id"] == "") || ($location["department_id"] == "0")))
		{
			$locationString .= " &gt; " . $location["department_name"];
		}

		if (isset($location["city_name"]) && $location["city_name"] != ""	&& (!isset($location["city_id"])	||	($location["city_id"] == "") || ($location["city_id"] == "0") ))
		{
			$locationString .= " &gt; " . $location["city_name"];
		}			

		return $locationString;
		
		//Select department info (if applicable)
		
		//Select town info (if applicable)
	}
	
	public function setFromPrepare()
	{
		//Select SQL
		var_dump($this->preparelist);
	}
}

?>