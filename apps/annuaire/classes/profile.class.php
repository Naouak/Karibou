<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * Display profile
 * 
 * @package applications
 */

class Profile
{
	protected $profile = false;
	protected $addresses = array();
	protected $phones = array();
	protected $emails = array();
	
	function __construct($profile)
	{
		$this->profile = $profile;
	}
	
	function getId()
	{
		return $this->profile['id'];
	}
	function getProfile()
	{
		return $this->profile;
	}
	function getAddresses()
	{
		return $this->addresses;
	}
	function getPhones()
	{
		return $this->phones;
	}
	function getEmails()
	{
		return $this->emails;
	}
	function setProfile($profile)
	{
		$this->profile = $profile;
	}
	function setAddresses($addresses)
	{
		$this->addresses = $addresses;
	}
	function setPhones($phones)
	{
		$this->phones = $phones;
	}
	function setEmails($emails)
	{
		$this->emails = $emails;
	}
		
	

}

?>