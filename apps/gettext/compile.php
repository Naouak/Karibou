<?php
/**
 * Configuration
 */
if (isset($_GET['l'])) {
	define('LANG',			$_GET['l']);
} elseif (isset($_SESSION['l'])) {
	define('LANG',			$_SESSION['l']);
} else {
	define('LANG',			'fr');
}
$_SESSION['l'] = LANG;

if (isset($_GET['d'])) {
	define('DISPLAY',			$_GET['d']);
} elseif (isset($_SESSION['d'])) {
	define('DISPLAY',			$_SESSION['d']);
} else {
	define('DISPLAY',			'fr');
}
$_SESSION['d'] = DISPLAY;

if (isset($_GET['f'])) {
	define('FILE',			$_GET['f']);
} elseif (isset($_SESSION['f'])) {
	define('FILE',			$_SESSION['f']);
} else {
	define('FILE',			'fr');
}
$_SESSION['f'] = FILE;

$GLOBALS['langfiles'] = array();

?>
<pre><a href="./index.php">Accueil</a> &gt; <strong>Langue : </strong><a href="?l=fr">fr</a>:<a href="?l=en">en</a> | <strong>Affichage : </strong><a href="?d=li">li</a>:<a href="?d=po">po</a>:<a href="?d=msg">msg</a>:<a href="?d=dup">dup</a> | <strong>Fichier : </strong><a href="?f=oui">oui</a>:<a href="?f=non">non</a>
Configuration actuelle : <?=LANG?> | <?=DISPLAY?> | <?=FILE?> <?php if(FILE=='oui'){ echo '('.FILEFULLNAME.')';};?>
<hr />
<h1>Compilation des fichiers de traduction gettext (PO)</h1>
<?php


/**
 * Execution
 */
define('PATH', '../../apps');
define('LOCALEPATH', '../locale');

//Récupération des fichiers de langue
$files = do_dir(PATH);

//Concaténation de fichiers de langue
foreach($files as $lang => $f) {
	$cmd_msgcat = $cmd_msgcat_fr = 'msgcat ';
	foreach ($f as $file) {
		$cmd_msgcat .= $file." ";
	}
	$cmd_msgcat .= ' -o full.'.$lang.'.po';
	echo "Execution de <strong>".$cmd_msgcat."</strong>\n";
	passthru($cmd_msgcat, $return);
	echo "(...OK)\n";
}

echo "<hr />";

//Compilation
if (!is_dir(LOCALEPATH)) {
	mkdir(LOCALEPATH);
}
foreach($files as $lang => $f) {
	if (!is_dir(LOCALEPATH.'/'.$lang)) {
		mkdir(LOCALEPATH.'/'.$lang);
	}
	if (!is_dir(LOCALEPATH.'/'.$lang.'/LC_MESSAGES')) {
		mkdir(LOCALEPATH.'/'.$lang.'/LC_MESSAGES');
	}
	$cmd_msgfmt = 'msgfmt full.'.$lang.'.po -o '.LOCALEPATH.'/'.$lang.'/LC_MESSAGES/messages.mo';
	echo "Execution de <strong>".$cmd_msgfmt."</strong>\n";
	passthru($cmd_msgfmt, $return);
	echo "(...OK)\n";
}

/**
 * Functions
 */
function do_file($file, $dir, $lang)
{	
	die;
}
 
function do_dir($dir)
{

	$array1 = array();
	
	$d = dir($dir);
	while (false !== ($entrysingle = $d->read())) {
		if ($entrysingle == '.' || $entrysingle == '..' || preg_match('/svn/', $entrysingle)) {
			continue;
		}
		
		$entry = $dir.'/'.$entrysingle;
	
		if (	preg_match('/'.str_replace('/','\/',str_replace('.','\.',PATH)).'\/[a-z]+\/languages\/([a-z]+).po/', $entry, $match) 
			||	preg_match('/'.str_replace('/','\/',str_replace('.','\.',PATH)).'\/_languages\/([a-z]+).po/', $entry, $match))  {
			//do_file($entry, $dir, LANG);

			//$regexp1 = '/'.str_replace('/','\/',str_replace('.','\.',PATH)).'\/([a-z]+)\/languages\/([a-z]+).po/';
			//if (preg_match($regexp, $entry, $match)) {
				//$application = $match[1];
				$lang = $match[1];
			
				if (!isset($array1[$lang])) {
				//var_dump($langfiles);
					$array1[$lang] = array();
					
				} else {
					echo "OK";
				}
				//return $entry;
				array_push ($array1[$lang],  $entry);
			//}
	
		} elseif (is_dir($entry)) {
			$array2 = do_dir($entry);
			$array1 = array_merge_recursive($array1, $array2);
		}
	}
	
	$d->close();
	return $array1;	
}
/*
	echo "<pre>";
	passthru('msgfmt -v ..\apps\_languages\en.po',$results);
	
var_dump($results);
*/
?>