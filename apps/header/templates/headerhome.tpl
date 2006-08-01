<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<base href="http://{$smarty.server.HTTP_HOST}{$smarty.server.SCRIPT_NAME|replace:"index.php":""}" />
	<title>
		##HEADER_PAGE_TITLE## :: ##KPOWERED##
	</title>
	<link rel="stylesheet" type="text/css" href="{$karibou.base_url}{$cssFile}" media="screen" title="Normal" />
{foreach item=style from=$styles}
	<link rel="alternate stylesheet" type="text/css" href="{$karibou.base_url}{$style.home_css}" media="screen" title="{$style.titre}" />
{/foreach}
	<script type="text/javascript" src="{$karibou.base_url}/themes/js/prototype.js"></script>
	<script type="text/javascript" src="{$karibou.base_url}/themes/js/scriptaculous.js"></script>
	<script type="text/javascript" src="{$karibou.base_url}/themes/js/karibou.js"></script>
	<script type="text/javascript">
{literal}
		function popup(adresse, nom, hauteur, largeur, haut, gauche){
			window.open(adresse, nom,'menubar=false, status=false, location=false, scrollbar=false, resizable=false, height='+hauteur+', width='+largeur+', top='+haut+', left='+gauche);
		}
		
		//Barre de navigation du site 
		var navCategories = new Array("Communicate","Organize","Share","Jobs","Admin");

		function LoadSiteNavigation() {
			HideAppsLinks();
		}

		function ShowAppsLinks(strMenu) {
		  HideAppsLinks();
		  document.getElementById(strMenu).style.visibility="visible";
		}

		function HideAppsLinks() {
			for(i in navCategories) {
				if (document.getElementById("menu"+navCategories[i])) {
					with(document.getElementById("menu"+navCategories[i]).style) {
						visibility="hidden";
					}
				}
			}
		}
{/literal}
	</script>
{hook name="html_head"}
</head>
<body onload="LoadSiteNavigation();">

{* HintBox support *}
<div id="hintbox">&nbsp;</div>
<script type="text/javascript" src="/themes/js/hintbox.js"></script>

<div id="container">
{*
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
*}
	<div id="main">
	<div class="leftdeco">
	<div class="rightdeco">
		
	<div id="content1">
	<div id="content">
	
{hook name="page_content_start"}
