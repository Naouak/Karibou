<?php


/**
 * Récupère la lise des fichiers de langue (contenus dans /apps/_languages et /apps/monappli/languages) dans un tableau pour exploitation
 */
function get_po($dir)
{

	$array1 = array();

	$d = dir($dir);
	
	while (false !== ($entrysingle = $d->read()))
	{
		if ($entrysingle == '.' || $entrysingle == '..' || preg_match('/svn/', $entrysingle))
		{
			continue;
		}
		
		$entry = $dir.'/'.$entrysingle;
	
		if (	preg_match('/\/[a-z]+\/languages\/([a-z]+).po/', $entry, $match) 
			||	preg_match('/\/_languages\/([a-z]+).po/', $entry, $match))  {
				$lang = $match[1];
			
				if (!isset($array1[$lang]))
				{
					$array1[$lang] = array();
				}
				array_push ($array1[$lang],  $entry);
		} elseif (is_dir($entry)) {
			$array2 = get_po($entry);
			$array1 = array_merge_recursive($array1, $array2);
		}
	}
	
	$d->close();
	return $array1;	
}

?>