<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * @package framework
 */
class KeyChainSession extends KeyChain
{
	protected $saved_data = array();

	function __construct(CurrentUser $user)
	{
		parent::__construct($user);

		if( isset($_SESSION['keychain_saved_data']) )
		{
			$this->saved_data = $_SESSION['keychain_saved_data'];
		}
	}
	
	function __destruct()
	{
		$_SESSION['keychain_saved_data'] = $this->saved_data;
		
		parent::__destruct();
	}
	
	function createStorage()
	{
		$this->saved_data = array();
	}
	
	function getData($name)
	{
		if( isset($this->saved_data[$name]) )
		{
			return $this->saved_data[$name];
		}
		return FALSE;
	}
	
	function setData($name, $data)
	{
		$this->saved_data[$name] = $data;
	}
	
	function getAllData()
	{
		return $this->saved_data;
	}

	function getNames()
	{
		$ret = array();
		foreach($this->saved_data as $name => $data)
		{
			$ret[] = $name;
		}
		return $ret;
	}
	
}

?>
