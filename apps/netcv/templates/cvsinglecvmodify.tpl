<div class="netcv">
	{include file="applinks.tpl"}

	<div class="breadcrumbs">
		##YOU_ARE_HERE##: <a href="{kurl app="netcv"}">##HOMEPAGE##</a> :: {if (isset($myNetCVGroup) && ($myNetCVGroup != FALSE))}<a href="{kurl app="netcv"}">CV &quot;{$myNetCVGroup->getInfo("name")}&quot;</a>{else}##CV_CREATION##{/if}
	</div>

{include file="messages.tpl"}

	{if ($myNetCVSingleCV != FALSE)}
		{*La section existe*}
		{assign var="netcvPageTitle" value="##CV_LANGUAGE_CREATION##"}
		{assign var="netcvFormText" value="##APPLY_MODIFICATIONS##"}
	{else}
		{*La section n existe pas et on va la creer*}
		{assign var="netcvPageTitle" value="##CV_VERSION_CREATION##"}
		{assign var="netcvFormText" value="##CREATE_THE_CV##"}
	{/if}
	
	<h1>{$netcvPageTitle}</h1>
	
	<form action="{kurl page="cvsinglecvsave"}" method="post">
		<input type="hidden" name="netcvSingleCVId" value="{$cvid}">
		<input type="hidden" name="netcvGroupId" value="{$gid}">
		<div class="Level1TitleAdd">
			<label for="netcvSingleCVLang">##CV_LANGUAGE_CREATION##:</label>
			<select name="netcvSingleCVLang">
{foreach key=code item=language from=$languages}  
			<option value="{$code}" {if (isset($myNetCVSingleCV) && ($myNetCVSingleCV != FALSE))}{if ($myNetCVSingleCV->getInfo("lang")==$code)}SELECTED{/if}{/if}>{$language}</option>
{/foreach} 
			</select>
		</div>
		<br />
		<div class="formAddTextButton">
			<input type="submit" value="{$netcvFormText}" name="post">
			(<a href="{kurl page=""}">##CANCEL##</a>)
		</div>
	</form>
</div>