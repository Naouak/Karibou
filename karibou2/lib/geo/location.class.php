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
class GeoLocation
{
	protected $country;	//Pays
	protected $county;	//Région
	protected $department;	//Département
	protected $city;	//Ville

	public function __construct($country, $county = FALSE, $department = FALSE, $city = FALSE)
	{
		
	}
	
	
	/* GET INFO FUNCTIONS */
	public function getCountryInfo ($key)
	{
		if (isset($this->country, $this->country[$key]))
		{
			return $this->country[$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getCountyInfo ($key)
	{
		if (isset($this->county, $this->county[$key]))
		{
			return $this->county[$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getDepartmentInfo ($key)
	{
		if (isset($this->department, $this->department[$key]))
		{
			return $this->department[$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getCityInfo ($key)
	{
		if (isset($this->city, $this->city[$key]))
		{
			return $this->city[$key];
		}
		else
		{
			return FALSE;
		}
	}
}

?>