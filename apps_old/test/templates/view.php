<pre>
<?php
/* Configure le script en hollandais */
//setlocale (LC_ALL, 'nl_NL');

/* Affiche : vrijdag 22 december 1978 */
echo strftime("%A %e %B %Y", mktime(0, 0, 0, 12, 22, 1978))."\n";
    
/* Essai de différentes valeurs possible pour l'allemand depuis &php; 4.3.0 */
$loc_de = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
echo "L'identifiant de l'allemand sur ce système est '$loc_de'";
?> 