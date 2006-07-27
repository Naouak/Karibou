<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 * Include du fichier de fonctions
 */
include(dirname(__FILE__).'/functions/compile.php');

/**
 *
 * @package applications
 **/
class GetTextCompile extends Model
{
	public function build()
	{
		if (!is_dir(KARIBOU_LOCALE_DIR)) {
			//mkdir(KARIBOU_LOCALE_DIR);
			Debug::kill(KARIBOU_LOCALE_DIR.' : R&eacute;pertoire inexistant.');
		}
		
		//Récupération des fichiers de langue
		$files = get_po(KARIBOU_APP_DIR);

		ob_start();
		echo "<ul>";
		//Concaténation de fichiers de langue
		foreach($files as $lang => $f) {
			$mergedfile = KARIBOU_CACHE_DIR.'/full.'.$lang.'.po';
			$cmd_msgcat = $cmd_msgcat_fr = 'msgcat ';
			foreach ($f as $file) {
				$cmd_msgcat .= $file." ";
			}
			$cmd_msgcat .= ' -o '.$mergedfile;
			echo "<li style=\"list-style: none; margin: 5px; border: 1px solid #999999; background-color: #efefef;\"><strong>Fusion</strong> des fichiers de langue (".$cmd_msgcat.")\n";
			passthru($cmd_msgcat, $return);
			if ($return > 0)
			{
				echo "&gt; &gt; &gt; <strong style=\"color: #dd6666;\">KO</strong>\n";
			}
			else
			{
				echo "&gt; &gt; &gt; <strong style=\"color: #33aa33;\">OK</strong>\n";
			}
			
			if (!is_dir(KARIBOU_LOCALE_DIR.'/'.$lang)) {
				mkdir(KARIBOU_LOCALE_DIR.'/'.$lang);
			}
			if (!is_dir(KARIBOU_LOCALE_DIR.'/'.$lang.'/LC_MESSAGES')) {
				mkdir(KARIBOU_LOCALE_DIR.'/'.$lang.'/LC_MESSAGES');
			}
			$cmd_msgfmt = 'msgfmt '.$mergedfile.' -o '.KARIBOU_LOCALE_DIR.'/'.$lang.'/LC_MESSAGES/messages.mo';
			echo "<li style=\"list-style: none; margin-bottom: 20px; border: 2px solid #999999; background-color: #efefef;\"><strong>Compilation</strong>	(".$cmd_msgfmt.")\n";
			passthru($cmd_msgfmt, $return);
			if ($return > 0)
			{
				echo "&gt; &gt; &gt; <strong style=\"color: #dd6666;\">KO</strong>\n";
			}
			else
			{
				echo "&gt; &gt; &gt; <strong style=\"color: #33aa33;\">OK</strong>\n";
			}
			
			//Suppression du fichier temporaire des langues fusionnées
			unlink($mergedfile);

		}

		echo "</ul>";
		$messages = ob_get_contents();
		ob_end_clean();
		$this->assign('messages', $messages);

	}
}

?>