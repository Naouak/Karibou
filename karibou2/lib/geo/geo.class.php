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
	
	public function setFromPrepare()
	{
		//Select SQL
		var_dump($this->preparelist);
	}
}

?>