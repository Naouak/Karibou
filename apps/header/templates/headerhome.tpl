<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<base href="http://{$smarty.server.HTTP_HOST}{$smarty.server.SCRIPT_NAME|replace:"index.php":""}" />
	<base href="http://{$smarty.server.HTTP_HOST}{$smarty.server.SCRIPT_NAME|replace:"index.php":""}" />
	<title>
		##HEADER_PAGE_TITLE## :: ##KPOWERED##
	</title>
	<link rel="stylesheet" type="text/css" href="{$smarty.server.SCRIPT_NAME|replace:"index.php":""}{$cssFile}" media="screen" title="Normal" />
{foreach item=style from=$styles}
	<link rel="alternate stylesheet" type="text/css" href="{$smarty.server.SCRIPT_NAME|replace:"index.php":""}{$style.home_css}" media="screen" title="{$style.titre}" />
{/foreach}
	<script type="text/javascript" src="{$smarty.server.SCRIPT_NAME|replace:"index.php":""}/themes/js/prototype.js"></script>
	<script type="text/javascript" src="{$smarty.server.SCRIPT_NAME|replace:"index.php":""}/themes/js/scriptaculous.js"></script>
	<script type="text/javascript" src="{$smarty.server.SCRIPT_NAME|replace:"index.php":""}/themes/js/karibou.js"></script>
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

{* HintBox support *}
<div id="hintbox">&nbsp;</div>
<script type="text/javascript" src="/themes/js/hintbox.js"></script>

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
			{*
			<li>
				<select>
					<option disabled>##DEFAULT_QUICKLINKS##</option>
					<option disabled>##APP_NEWS##</option>
					<option disabled>##APP_EMAIL##</option>
					<option disabled>##APP_DIRECTORY##</option>
					<option disabled>##APP_ADDRESSBOOK##</option>
					<option disabled>##APP_FILESHARE##</option>
					<option disabled>##APP_CALENDAR##</option>
					<option disabled>##APP_NETCV##</option>
					<option disabled>##APP_CONTACT##</option>
				</select>
			</li>
			*}
			{hook name="header_menu"}
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
