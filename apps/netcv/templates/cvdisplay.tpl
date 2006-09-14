{if ($myNetCVGroup->getInfo('diffusion') == 'private')}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>##NOT_ENOUGH_CONTENT_PRIVATE##</title>
		<meta name="robots" content="noindex, nofollow" />
	</head>
	<body>
	<h1>##PRIVATE_DIFFUSION##</h1>
	<p>
		##NETCV_PRIVATE_DIFFUSION_PAGEDESCRIPTION##
	</p>
	</body>
</html>
{elseif (
($myNetCVSingleCV->countSections() < $appconfig.minimum.sections) || 
($myNetCVSingleCV->countElements() < $appconfig.minimum.elements) ||
(($myNetCVUser->countPersonalInfo() - $appconfig.personalinfos.system) < $appconfig.minimum.personalinfos)
)}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>##NOT_ENOUGH_CONTENT_PRIVATE##</title>
		<meta name="robots" content="noindex, nofollow" />
	</head>
	<body>
{if ($myNetCVSingleCV->countSections() < $appconfig.minimum.sections) || 
($myNetCVSingleCV->countElements() < $appconfig.minimum.elements)}
		##NOT_ENOUGH_CONTENT_PRIVATE##<br /><br />
		<a href="{kurl app="netcv" page="cvsectionlist" cvid=$myNetCVSingleCV->getInfo("id") gid=$myNetCVGroup->getInfo("id")}">##BACK_TO_EDITING##</a>
		<br />
		<br />
{/if}
{if (($myNetCVUser->countPersonalInfo() - $appconfig.personalinfos.system) < $appconfig.minimum.personalinfos)}
		##NOT_ENOUGH_PERSONALINFO##<br /><br />
		<a href="{kurl app="netcv" page="personalinfo"}">##CLICK_HERE_TO_MODIFY_YOUR_PERSOINFO##</a>	
{/if}
	</body>
</html>
{else}
	{*Affichage du CV*}
	{include file="cvdisplayheader.tpl"}

	{if (isset($preview) && $preview == TRUE)}
	<div id="backToNetCV" onclick=location.href="{$smarty.server.HTTP_REFERER}">
		<p>
			<a href="{$smarty.server.HTTP_REFERER}">##HISTORY_BACK##</a>
			<br />
		</p>
	</div>
	{/if}

	{include file="messages.tpl"}
		
	{assign var="mySections" value=$myNetCV->child}
	<div id="container">
		<div class="lang">
			{section name=l loop=$myNetCVSingleCVList}
    			{assign var="singleCV" value=$myNetCVSingleCVList[l]}
{if isset($myNetCVSingleCVList[l])}
    {if ($myNetCVSingleCV->getInfo('lang') != "")}
		{if $hostnameAccess}
		<a href="{kurl page="" request=$singleCV->getInfo('lang')}">{$myNetCVLanguage->getNameByCode($singleCV->getInfo("lang"))}</a>
		{else}
		<a href="{kurl page="cv" hostname=$myNetCVGroup->getInfo('hostname') lang=$singleCV->getInfo('lang') preview=1}">{$myNetCVLanguage->getNameByCode($singleCV->getInfo("lang"))}</a>
		{/if}
    {/if}
{/if}
			{/section}
		</div>
	{*Affichage des informations de l utilisateur*}
		 <div id="boxTop">
		  <div id="name">
		   <h1>{$myNetCVPersonalInfo.firstname} {$myNetCVPersonalInfo.lastname}</h1>
		  </div>
		  <div id="infos">
			 <div id="address">
	{$myNetCVPersonalInfo.address1stline} {$myNetCVPersonalInfo.addresscitycode} {$myNetCVPersonalInfo.addresscity}
			 </div>
			 <div id="phone">
	{$myNetCVPersonalInfo.phonemobile}
			 </div>
			 <div id="email">
				{*if ($myNetCVGroup->getInfo("emailDisplay") == "show")*}
					<a href="mailto:{$myNetCVPersonalInfo.email}">{$myNetCVPersonalInfo.email}</a>
				{*elseif ($myNetCVGroup->getInfo("emailDisplay") == "form")}
{*$myNetCVGroup->getInfo("hostname")}
{*$myNetCVSingleCV->getInfo("lang")}
					<a href="{*{kurl page="cvcontactform" hostname=$myNetCVGroup->getInfo("hostname")}{* lang=$myNetCVSingleCV->getInfo("lang")}">contact</a>
				{/if*}
			 </div>
			 <div id="other_infos">
	{$myNetCVPersonalInfo.otherinfos}
			 </div>
		  </div>
		  <div id="photo">
		  </div>
		  <div id="jobTitle">
		   <h1><span>{$myNetCVPersonalInfo.jobtitle}</span></h1>
		  </div>
		 </div>
		 
	{*Affichage du contenu du CV*}
	{if ($mySections|@count > 0)}
		{section name=i loop=$mySections step=1}
			{assign var="aSection" value=$mySections[i]}
		<div class="boxTitle">
			<h1>{$aSection->getVar("infos")}{if $aSection->getVar("infos") == ""}&nbsp;{/if}</h1>
		</div>
			{if ($aSection->child|@count > 0)}
		<div class="boxContent">
				{section name=j loop=$aSection->child step=1}
					{assign var="anItem" value=$aSection->child[j]}
				<div class="boxContentLevel1">
					<h2>{if isset($anItem)}{$anItem->getVar("infos")}{if $anItem->getVar("infos") == ""}&nbsp;{/if}{else}&nbsp;{/if}</h2>
					<h3>{if isset($anItem->child[0])}{$anItem->child[0]->getVar("infos")}{if $anItem->child[0]->getVar("infos") == ""}&nbsp;{/if}{else}&nbsp;{/if}</h3>
					<p><span>{if isset($anItem->child[0]->child[0])}{$anItem->child[0]->child[0]->getVarXHTML("infos")}{if $anItem->child[0]->child[0]->getVar("infos") == ""}&nbsp;{/if}{else}&nbsp;{/if}</span></p>
				</div>
				{/section}
		</div>
			{/if}
		{/section}
	{/if}
	</div>
	
	{include file="cvdisplayfooter.tpl"}
{/if}
