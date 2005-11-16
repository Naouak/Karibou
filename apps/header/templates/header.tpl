<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>
		##HEADER_PAGE_TITLE## :: ##KPOWERED##
	</title>
{hook name="html_head"}
	<link rel="stylesheet" type="text/css" href="{$cssFile}" media="screen" title="Normal" />
{foreach item=style from=$styles}
	<link rel="alternate stylesheet" type="text/css" href="{$style.home_css}" media="screen" title="{$style.titre}" />
{/foreach}
	<script src="/themes/js/prototype.js"></script>
	<script src="/themes/js/scriptaculous.js"></script>
	<script type="text/javascript">
{literal}
		function popup(adresse, nom, hauteur, largeur, haut, gauche){
			window.open(adresse, nom,'menubar=false, status=false, location=false, scrollbar=false, resizable=false, height='+hauteur+', width='+largeur+', top='+haut+', left='+gauche);
		}
{/literal}
	</script>
</head>
<body>
<div id="container">
	<div id="top">
	<div class="leftdeco">
	<div class="rightdeco">
		<span id="surnom">
			{$currentUserName}
		</span>
		<span id="menujs">
			<a href="/">Accueil</a>
			<a href="{kurl app="mail"}">Mail</a>
			<a href="{kurl app="calendar"}">Calendrier</a>
			<a href="{kurl app="news"}">News</a>
			<a href="{kurl app="minichat"}">MiniChat</a>
			<a href="{kurl app="permissions"}">Permissions</a>
			<a href="{kurl app="annuaire"}">Annuaire</a>
			<a href="{kurl app="netcv"}">NetCV</a>
			<a href="{kurl app="preferences"}">Preferences</a>
			<a href="{kurl app="test"}">Test</a>
	 	</span>
		<span id="icones">
			petites icones
		</span>
		<span id="connectes">
			{$linkConnectedUsers}
		</span>
	</div>
	</div>
	</div>
	
	<div id="banner">
		<div class="leftdeco">
			<div class="rightdeco"></div>
		</div>
	</div>
	
	<div id="main">
	<div class="leftdeco">
	<div class="rightdeco">
		
	<div id="content1">
	<div id="content">
	
{hook name="page_content_start"}		
