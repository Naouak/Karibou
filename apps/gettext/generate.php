<?php
define('PATH',			'../../apps');
$extensions = array('tpl');

/**
 * Execution
 */
echo "<pre>";
if (isset($_GET['key'])) {
	echo "La clé de traduction <strong>".$_GET['key']."</strong> se trouve dans les fichiers suivants :\n";
}
echo "<ul>";
do_dir(PATH);
echo "</ul>";

/**
 * Fonctions
 */
function fs($str)
{
	$str = stripslashes($str);
	$str = str_replace('"', '\"', $str);
	$str = str_replace("\n", '\n', $str);
	return $str;
}

// rips gettext strings from $file and prints them in C format
function do_file($file)
{
	$content = @file_get_contents($file);

	if (empty($content)) {
		return;
	}

	if (isset($_GET['key'])) {
		preg_match_all(
				"/##(".$_GET['key'].")##/",
				$content,
				$matches
		);
	} else {
		preg_match_all(
				"/##([^{##}]+)##/",
				$content,
				$matches
		);
	}

	if (count($matches[0])>0) {
		echo "<li><strong>".count($matches[0])."</strong> -> $file\r\n</li>";
	}

	if (!isset($_GET['key'])) {
		for ($i=0; $i < count($matches[0]); $i++) {
			echo fs($matches[1][$i])."\r\n";
		}
	}

}

// go through a directory
function do_dir($dir)
{
	$d = dir($dir);

	while (false !== ($entry = $d->read())) {
		if ($entry == '.' || $entry == '..' || preg_match('/svn/', $entry)) {
			continue;
		}

		$entry = $dir.'/'.$entry;

		if (is_dir($entry)) {
			do_dir($entry);
		} else {
			$pi = pathinfo($entry);
			
			if (isset($pi['extension']) && in_array($pi['extension'], $GLOBALS['extensions'])) {
				do_file($entry);
			}
		}
	}

	$d->close();
}

?>