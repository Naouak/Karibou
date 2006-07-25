<h1>Gestion des traductions sous gettext</h1>
<h2>Initialisation / Mise à jour des fichiers de traduction</h2>
Le fichier de traduction est nécessaire pour localiser une application. C'est un fichier texte ayant pour extension .po et contenant les éléments de traduction (identifiant : msgid et traduction : msgstr).<br />
<a href="generate.php">Générer (ou mettre à jour) les fichiers de traduction des applications</a><br />

<h2>Compilation du fichier PO</h2>
Une fois le fichier PO créé, il faut le compiler pour qu'il soit exploitable par gettext.<br />
<a href="compile.php">Compiler le fichier PO (pour prise en compte des traduction)</a><br />


<hr />


<div style="color: #acacac;">
<h2>Création du PO (fichier traducteurs) à partir des fichiers de langue (languages.xml)</h2>
<a href="xml2po.php">Accéder au fichier de création du PO</a><br />
Ce fichier requiert des modifications (fichier de sortie, type de sortie...).
<br />
<strong>Il ne doit être utilisé que pour l'import initial des traductions et n'aura plus lieu d'être lors de la suppression des fichiers de langue (languages.xml)</strong>
</div>