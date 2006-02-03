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

	public function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
		
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
}

?>