<?php
class NetCVLanguage {
	public $languages;
	
	function __construct () {
		$languages = array();
		$languagescodes = new XMLCache(KARIBOU_CACHE_DIR.'/languagecodes');
		if( $languagescodes->loadURL(KARIBOU_DIR.'/data/languagecodes.xml') )
		{
			$xml = $languagescodes->getXML();
				if( isset($xml->language) )
				{
					foreach($xml->language as $language)
					{
						$this->languages[$language->attributes["code"]] = $language->attributes["name"];
					}
				}
		}
	}
	
	function getNameByCode ($code)
	{
		if (isset($this->languages[$code]))
		{
			return $this->languages[$code];
		}
		else
		{
			return FALSE;
		}
	}
	
	function returnLanguages ()
	{
		return $this->languages;
	}
}
?>