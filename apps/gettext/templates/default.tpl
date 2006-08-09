<h1>Gestion des traductions sous gettext</h1>
<h2>Compilation du fichier PO</h2>
Une fois le fichier PO cr&eacute;&eacute;, il faut le compiler pour qu'il soit exploitable par gettext.<br />
<a href="{kurl action="compile"}">Compiler le fichier PO (pour prise en compte des traduction)</a>
{khint langmessage='Il faut pour cela que vos r&eacute;pertoires de cache (smarty) et locale soient accessibles en &eacute;criture.'}

{*
<br />
<hr />
<br />
<br />

<h2>Initialisation / Mise &agrave; jour des fichiers de traduction</h2>
Le fichier de traduction est n&eacute;cessaire pour localiser une application. C'est un fichier texte ayant pour extension .po et contenant les &eacute;l&eacute;ments de traduction (identifiant : msgid et traduction : msgstr).<br />
<a href="generate.php">G&eacute;n&eacute;rer (ou mettre &agrave; jour) les fichiers de traduction des applications</a><br />
<br />

<div style="color: #acacac;">
<h2>Cr&eacute;ation du PO (fichier traducteurs) &agrave; partir des fichiers de langue (languages.xml)</h2>
<a href="xml2po.php">Acc&eacute;der au fichier de cr&eacute;ation du PO</a><br />
Ce fichier requiert des modifications (fichier de sortie, type de sortie...).
<br />
<strong>Il ne doit être utilis&eacute; que pour l'import initial des traductions et n'aura plus lieu d'être lors de la suppression des fichiers de langue (languages.xml)</strong>
</div>
*}