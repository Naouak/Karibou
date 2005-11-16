<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>
		Karibou V2 :: ##HEADER_PAGE_TITLE##
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
	
	<div id="main">
	<div class="leftdeco">
	<div class="rightdeco">
		
	<div id="content1">
	<div id="content">
	
