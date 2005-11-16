<?php
/**
 * @copyright 2005 DaToine
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 **/
 
/** 
 * Load languages files for Smarty parsing
 *
 * @todo indent properly !
 * @package framework
 */
class LanguageManager
{
	protected $translationTable; //Le dictionnaire des traductions
	protected $currentLanguage; //La langue dans laquelle les traductions doivent etre donnees

	function __construct()
	{
		$this->currentLanguage = FALSE;
	
		if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
		{
			//Karibou supported languages
			$languagesarray = array(
			"en"		=> "English",
			"en-gb"	=> "English/United Kingdom",
			"en-us"	=> "English/United Satates",
			"en-au"	=> "English/Australian",
			"en-ca"	=> "English/Canada",
			"en-nz"	=> "English/New Zealand",
			"en-ie"	=> "English/Ireland",
			"en-za"	=> "English/South Africa",
			"en-jm"	=> "English/Jamaica",
			"en-bz"	=> "English/Belize",
			"en-tt"	=> "English/Trinidad",
			"fr"		=> "French",
			"fr-be"	=> "French/Belgium",
			"fr-fr"	=> "French/France",
			"fr-ch"	=> "French/Switzerland",
			"fr-ca"	=> "French/Canada",
			"fr-lu"	=> "French/Luxembourg"
			);

			$acceptlanguages = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
			
			$pos = strpos($acceptlanguages,";");  
			if ($pos > 0)
			{
				$acceptlanguages = substr($acceptlanguages,0,$pos);
			}
			
			$languages = split(",", $acceptlanguages);
			
			//List all languages supported by the browser, and check if the intranet can support that
			//language. If it can, then it calls setCurrentLanguage.
			reset($languages);
			while ( ($language = each($languages)) && ($this->getCurrentLanguage() == FALSE) )
			{
				$dashPos = strpos($language["value"],"-");
				if ($dashPos > 0)
				{
					$languageCode = substr($language["value"], 0, $dashPos);
				}
				else
				{
					$languageCode = $language["value"];
				}

				if (isset($languagesarray[$languageCode]) && ($languagesarray[$languageCode] != "") )
				{
					$this->setCurrentLanguage ($languageCode);
				}
				else
				{
					//Unsupported language
				}
			}
		}
		
		//If no language has been set, it sets the default language
		if ($this->getCurrentLanguage() == FALSE)
		{
			$this->setCurrentLanguage();
		}
	}

	/**
	 * loadLanguages
	 * Methode d'ajout des traductions au dictionnaire translationTable
	 * Il serait possible de cacher les traductions pour optimiser le fonctionnement
	 * @param &$xml
	 **/
	function loadLanguages ($xml)
	{
		if( isset( $xml->sentence ) )
		{
			foreach($xml->sentence as $sentence)
			{
				if( isset( $sentence->translation ) )
				{
					foreach($sentence->translation as $translation)
					{
						//Backward compatibility (the xml parser used only attributes)
						if (isset($translation->text) && ($translation->text != ""))
						{
							$this->addSentence($translation["language"], $sentence["key"], $translation->text);
						}
						else
						{						
							$this->addSentence($translation["language"], $sentence["key"], $translation["value"]);
						}
					}
				}
			}
		}
	}
	
	/**
	 * Methode addSentence
	 * Ajoute la phrase au dictionnaire (translationTable)
	 * @params $language, $key, $value
	 */
	function addSentence ($language, $key, $value)
	{
		if( !isset($this->translationTable[$language]) ) $this->translationTable[$language] = array();
		$this->translationTable[$language][$key] = $value;
	}

	/**
	 * Methode getCurrentLanguage
	 * Recupere la langue en cours
	 * @return
	 */
	function getCurrentLanguage ()
	{
	  return $this->currentLanguage;
	}
	
	/**
	 * Methode setCurrentLanguage
	 * Assigne la langue en cours, si aucune langue
	 * n'est passée en paramètre, la langue par défaut est utilisée
	 * @param $language
	 * @return
	 */
	function setCurrentLanguage ($language = "en")
	{
		$this->currentLanguage = $language;
		switch ($language)
		{
			case "fr":
				setlocale (LC_ALL, 'fr_FR.UTF-8');
			break;
			case "en":
				setlocale (LC_ALL, 'en_US.UTF-8');
			break;
			default:
				setlocale (LC_ALL, 'en_US.UTF-8');
			break;
		}
	}

	/**
	 * Methode getTranslation
	 * Traduit le mot cle $key
	 * @param $key
	 * @return
	 */
	function getTranslation ($key)
	{
		if (isset($this->translationTable[$this->currentLanguage], $this->translationTable[$this->currentLanguage][$key]))
		{
			return $this->translationTable[$this->currentLanguage][$key];
		}
		else
		{
			Debug::display("LanguageManager: $key doesn't exist in ".$this->currentLanguage.".");
			return $key;
		}

	}

}
?>