<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once 'smarty/Smarty.class.php';

require_once dirname(__FILE__).'/smarty/kurl.function.php';
require_once dirname(__FILE__).'/smarty/translate.function.php';
require_once dirname(__FILE__).'/smarty/insertheader.function.php';

/**
 * Smarty template engine customization
 * 
 * @package framework
 */
class KSmarty extends Smarty
{
	protected $kapp;
	protected $appList;

	protected $language;
	
	protected $languageManager;
	protected $hookManager;
	
	function KSmarty($kapp, AppList $appList, LanguageManager $languageManager, 
		HookManager $hookManager, $language="")
	{
		$this->Smarty();
		$this->kapp = $kapp;
		$this->appList = $appList;
		
		$this->register_function('kurl', 'smarty_function_kurl');
		$this->register_function('translate', 'smarty_function_translate');
		
		$this->hookManager = $hookManager;
		$displayHook = array(&$this, 'displayHook');
		$this->register_function('hook', $displayHook);

		$this->languageManager = $languageManager;
		
		$prefilterTranslation = array (&$this,'prefilterTranslation');
		$this->register_prefilter($prefilterTranslation);
	}
	
	function fetch($_smarty_tpl_file, $_smarty_cache_id = null, $_smarty_compile_id = null, $_smarty_display = false) {
	  // We need to set the cache id and the compile id so a new script will be
	  // compiled for each language. This makes things really fast ;-)
	  $_smarty_compile_id = $this->languageManager->getCurrentLanguage().'-'.$_smarty_compile_id;
	  $_smarty_cache_id = $_smarty_compile_id;

	  // Now call parent method
	  return parent::fetch( $_smarty_tpl_file, $_smarty_cache_id, $_smarty_compile_id, $_smarty_display );
	}
	
	function getApp()
	{
		return $this->kapp;
	}
	function getAppList()
	{
		return $this->appList;
	}
	
	
	/**
   * test to see if valid cache exists for this template
   *
   * @param string $tpl_file name of template file
   * @param string $cache_id
   * @param string $compile_id
   * @return string|false results of {@link _read_cache_file()}
   */
   function is_cached($tpl_file, $cache_id = null, $compile_id = null)
   {
			if (!$this->caching)
					 return false;

			if (!isset($compile_id)) {
					 $compile_id = $this->languageManager->getCurrentLanguage().'-'.$this->compile_id;
					 $cache_id = $compile_id;
			}

			return parent::is_cached($tpl_file, $cache_id, $compile_id);
   }
   
   
   
  /**
   * prefilterTranslation()
   * Methode gerant la traduction du document
   *
   * @param $tpl_source
   * @return
   **/
  function prefilterTranslation ($tpl_source, &$smarty)
  {
	$_translateFunction = array (&$this, '_translate_key');
	
	return preg_replace_callback('/##(.+?)##/', $_translateFunction, $tpl_source);
  }

  /**
   * Methode traduisant le mot cle donne en parametre
   * @param $key
   * @return
   */
	function _translate_key($key)
	{
		return $this->translate_key($key[1]);
	}
	function translate_key($key)
	{
		return $this->languageManager->getTranslation($key);
	}


	function displayHook($param)
	{
		if (isset($param["name"]))
		{
			$this->hookManager->display($param["name"]);
		}
	}

}

?>
