<div class="netcv">
	{assign var="mySection" value=$myNetCV->returnChildById($pid)}
	{if (($mySection->child|@count) > 0)}
		{*L element existe*}
		{assign var="myTitleItem" value=$mySection->returnChildById($id)}
		
		{if (($myTitleItem->child|@count) > 0)}
			{assign var="mySubtitleItem" value=$myTitleItem->child[0]}
			{if (($mySubtitleItem->child|@count) > 0)}
				{assign var="myDescriptionItem" value=$mySubtitleItem->child[0]}
				{assign var="myTitleItemInfos" value=$myTitleItem->infos}
			{/if}
		{/if}
	{else}
		{*L element n existe pas*}
	{/if}
	
	{if ($myTitleItem != FALSE)}
		{*L'élément existe*}
		{assign var="netcvPageTitle" value="##MODIFICATION_OF_THE_ELEMENT##: $myTitleItemInfos"}
		{assign var="netcvFormText" value="##APPLY_MODIFICATIONS##"}
	{else}
		{*L'élément n'existe pas et on va le creer*}
		{assign var="netcvPageTitle" value="##ELEMENT_CREATION##"}
		{assign var="netcvFormText" value="##CREATE_ELEMENT##"}
	{/if}
	
	<div class="breadcrumbs">
		##YOU_ARE_HERE##: <a href="{kurl page=""}">##HOMEPAGE##</a> :: <a href="{kurl page=""}">CV &quot;{$myNetCVGroup->getInfo("name")}&quot;</a> :: <a href="{kurl app="netcv" page="cvsectionlist" cvid=$cvid gid=$gid}">{$myNetCVLanguage->getNameByCode($myNetCVSingleCV->getInfo("lang"))}</a> :: <a href="{kurl page="cvitemlist" pid=$pid cvid=$cvid gid=$gid}">&quot;<em>{$mySection->getVar("infos")}</em>&quot;</a> :: {if (isset($myTitleItem->infos))}<a href="{kurl page="cvitemmodify" gid=$gid cvid=$cvid pid=$pid id=$id}">&quot;<em>{$myTitleItem->getVar("infos")}</em>&quot;</a>{else}##ELEMENT_CREATION##{/if}
	</div>

	{include file="messages.tpl"}
	
	<h1>{$netcvPageTitle}</h1>
	
	<form action="{kurl page="cvitemsave"}" method="post">
	  <input type="hidden" name="pid" value="{$pid}">
	  <input type="hidden" name="netcvSingleCVId" value="{$cvid}">
	  <input type="hidden" name="netcvGroupId" value="{$gid}">
	  <div class="Level2ContentEdit">
		##ELEMENT_TITLE##:
		<input class="netcvTitleItem" type="text" name="netcvTitleItem" size="50" value="{if (isset($myTitleItem->infos))}{$myTitleItem->getVar("infos")|escape:"html"}{/if}">
		<input type="hidden" name="netcvTitleItemId" value="{if (isset($myTitleItem->infos))}{$myTitleItem->getVar("id")}{/if}">
		<br />
		##ELEMENT_SUBTITLE##:
		<input class="netcvSubtitleItem" type="text" name="netcvSubtitleItem" size="70" value="{if (isset($mySubtitleItem->infos))}{$mySubtitleItem->getVar("infos")|escape:"html"}{/if}">
		<input type="hidden" name="netcvSubtitleItemId" value="{if (isset($mySubtitleItem))}{$mySubtitleItem->getVar("id")}{/if}">
		<br />
		##ELEMENT_DESCRIPTION##:
		<textarea class="netcvDescriptionItem" name="netcvDescriptionItem" rows="10" cols="40">{if (isset($myDescriptionItem->infos))}{$myDescriptionItem->getVar("infos")|escape:"html"}{/if}</textarea>
		<input type="hidden" name="netcvDescriptionItemId" value="{if (isset($myDescriptionItem))}{$myDescriptionItem->getVar("id")}{/if}">
		<br />
		<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
		<br /><br />
	  </div>
	
	  <div class="formAddTextButton">
		<input type="submit" value="{$netcvFormText}" name="add">
			(<a href="{kurl page="cvitemlist" pid=$pid cvid=$cvid gid=$gid}">##BACK##</a> - <a href="{kurl page="cvsectionlist" cvid=$cvid gid=$gid}">##BACK_TO_SECTIONS##</a>)
	  </div>
	</form>
</div>
