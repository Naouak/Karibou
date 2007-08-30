<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once KARIBOU_SMARTY_DIR.'/Smarty.class.php';

require_once dirname(__FILE__).'/smarty/kurl.function.php';
require_once dirname(__FILE__).'/smarty/translate.function.php';
require_once dirname(__FILE__).'/smarty/insertheader.function.php';
require_once dirname(__FILE__).'/smarty/userlink.function.php';
require_once dirname(__FILE__).'/smarty/khint.function.php';
require_once dirname(__FILE__).'/smarty/t.block.php';
require_once dirname(__FILE__).'/smarty/find.modifier.php';
require_once dirname(__FILE__).'/smarty/highlight.modifier.php';

require_once dirname(__FILE__).'/smarty/SmartyValidate/SmartyValidate.class.php';

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
	
	protected $hookManager;
	
	protected $currentLanguage;
	
	function KSmarty(AppList $appList, $hookManager, $currentLanguage="")
	{
		$this->Smarty();
		$this->appList = $appList;
		$this->currentLanguage = $currentLanguage;
		
		$this->register_function('kurl', 		'smarty_function_kurl');
		$this->register_function('translate', 	'smarty_function_translate');
		$this->register_function('userlink', 	'smarty_function_userlink');
		$this->register_function('khint', 		'smarty_function_khint');
		$this->register_block	('t', 			'smarty_block_t');
        $this->register_modifier('find', 		'smarty_modifier_find');
        $this->register_modifier('highlight',	'smarty_modifier_highlight');
		
		array_push ($this->plugins_dir, dirname(__FILE__).'/smarty/SmartyValidate/plugins');
		
		if ($hookManager !== FALSE)
		{
			$this->hookManager = $hookManager;
		}
		$displayHook = array(&$this, 'displayHook');
		$this->register_function('hook', $displayHook);

		
		//Utilisation d'un prefilter et non d'un postfilter pour permettre la compatibilité avec 
		//la version précédente du gestionnaire de traductions (possibilité d'intégration de variables smarty)
		$prefilterTranslation = array (&$this,'prefilterTranslation');
		$this->register_prefilter($prefilterTranslation);

		$this->force_compile=TRUE; //charles_modif

		
	}
	
	function fetch($_smarty_tpl_file, $_smarty_cache_id = null, $_smarty_compile_id = null, $_smarty_display = false) {
	  // We need to set the cache id and the compile id so a new script will be
	  // compiled for each language. This makes things really fast ;-)
	  $_smarty_compile_id = $this->currentLanguage.'-'.$_smarty_compile_id;
	  $_smarty_cache_id = $_smarty_compile_id;

	  // Now call parent method
	  return parent::fetch( $_smarty_tpl_file, $_smarty_cache_id, $_smarty_compile_id, $_smarty_display );
	}
	
	function getApp()
	{
		return $this->kapp;
	}
	function setApp($app)
	{
		$this->kapp = $app;
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
					 $compile_id = $this->currentLanguage.'-'.$this->compile_id;
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
		return gettext($key);
	}


	function displayHook($param)
	{
		if (isset($param["name"]))
		{
			$this->hookManager->display($param["name"]);
		}
	}
	
	function quick_hack_clear_values()
	{
		unset($this->_tpl_vars['config']);
	}

}

?>
