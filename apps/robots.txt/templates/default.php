<?
	if ($GLOBALS['config']['netcv']['cvdisplay'] !== FALSE)
	{
		//Affichage d'un CV donc on affiche un robots.txt vide pour laisser NetCV décider (balises dans le header du CV)
	}
	else
	{
		//Affichage de l'Intranet, pas d'indexation
		echo '<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">'."\n";
		echo '<META NAME="GOOGLEBOT" CONTENT="NOINDEX, FOLLOW">'."\n";
	}
?>