<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>
		##HEADER_PAGE_TITLE## :: ##KPOWERED##
	</title>
	<link rel="stylesheet" type="text/css" href="{$cssFile}" media="screen" title="Normal" />
{foreach item=style from=$styles}
	<link rel="alternate stylesheet" type="text/css" href="{$style.home_css}" media="screen" title="{$style.titre}" />
{/foreach}
	<script src="/themes/js/prototype.js"></script>
	<script src="/themes/js/scriptaculous.js"></script>
	<script src="/themes/js/karibou.js"></script>
	<script type="text/javascript">
{literal}
		function popup(adresse, nom, hauteur, largeur, haut, gauche){
			window.open(adresse, nom,'menubar=false, status=false, location=false, scrollbar=false, resizable=false, height='+hauteur+', width='+largeur+', top='+haut+', left='+gauche);
		}
{/literal}
	</script>
{hook name="html_head"}
</head>
<body>
<div id="container">
	<div id="top">
	<div class="leftdeco">
	<div class="rightdeco">
		<span id="surnom">
		{if $currentUserName}
			{$currentUserName}
			( <a href="{kurl app="login" action="logout"}">##LOGOUT##</a> )
		{else}
		&nbsp;
		{/if}
		</span>
		<ul class="menu">
			<li><a href="{kurl app=""}">##APP_HOME##</a></li>
			<li><a href="{kurl app="news"}">##APP_NEWS##</a></li>
			<li><a href="{kurl app="mail"}">##APP_EMAIL##</a></li>
			<li><a href="{kurl app="annuaire"}">##APP_DIRECTORY##</a></li>
			<li><a href="{kurl app="addressbook"}">##APP_ADDRESSBOOK##</a></li>
			<li><a href="{kurl app="fileshare"}" class="highlight">##APP_FILESHARE##</a></li>
			<li><a href="{kurl app="calendar"}">##APP_CALENDAR##</a></li>
			<li><a href="{kurl app="netcv"}">##APP_NETCV##</a></li>
			<li><a href="{kurl app="wiki"}">Wiki</a></li>
			<li><a href="{kurl app="contact"}">Contact</a></li>
	 	</ul>
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
