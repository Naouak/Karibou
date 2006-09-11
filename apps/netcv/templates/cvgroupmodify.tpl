<div class="netcv">
	{assign var="myNetCVGroup" value=$myNetCVGroupList->returnGroupById($gid)}

	{include file="applinks.tpl"}

	<div class="breadcrumbs">
		##YOU_ARE_HERE##: <a href="{kurl app="netcv"}">##HOMEPAGE##</a> :: {if (isset($myNetCVGroup) && $myNetCVGroup != FALSE)}<a href="{kurl app="netcv"}">CV &quot;{$myNetCVGroup->getInfo("name")}&quot;</a>{else}##CREATION_OF_A_NEW_CV##{/if}
	</div>

	{if ($myNetCVGroup != FALSE)}
		{*Le groupe de CV existe*}
		{assign var="netcvPageTitle" value="##MODIFY_CV_SETTINGS##"}
		{assign var="netcvFormText" value="##APPLY_MODIFICATIONS##"}
	{else}
		{*Le groupe de CV n existe pas et on va le creer*}
		{assign var="netcvPageTitle" value="##CREATION_OF_A_NEW_CV##"}
		{assign var="netcvFormText" value="##CREATE_THE_CV##"}
	{/if}
	
	<h1>{$netcvPageTitle}</h1>

{include file="messages.tpl"}
	
	<div class="helper">
		##HELPER_CV_SETTINGS##
	</div>

	<form action="{kurl page="cvgroupsave"}" method="post">
		<input type="hidden" name="gid" value="{$gid}">
		<div class="Level1TitleAdd">
			<strong>##CV_NAME##</strong>
			<br />
			<input type="text" name="netcvGroupName" size="30" value="{if (isset($myNetCVGroup) && ($myNetCVGroup != FALSE))}{$myNetCVGroup->getInfo("name")|escape:"html"}{else}{$currentGroupInfo.title|escape:"html"}{/if}">
			<br />
			<br />			
			<strong>##CV_ADDRESS##</strong>
			<br />
			http://<input type="text" name="netcvGroupHostName" size="20" value="{if (isset($myNetCVGroup) && ($myNetCVGroup != FALSE))}{$myNetCVGroup->getInfo("hostname")}{else}{$currentGroupInfo.hostname}{/if}">.{$config.host}
			<br /><br />
			<strong>##CV_DIFFUSION##</strong>
			<br />
			<select name="netcvGroupDiffusion">
				<option value="public" {if ((($myNetCVGroup != FALSE) && ($myNetCVGroup->getInfo("diffusion") == "public")) || ($currentGroupInfo.diffusion == "public"))}SELECTED{/if}>##PUBLIC_DIFFUSION##</option>
				<option value="nocrawl" {if ((($myNetCVGroup != FALSE) && ($myNetCVGroup->getInfo("diffusion") == "nocrawl")) || ($currentGroupInfo.diffusion == "nocrawl"))}SELECTED{/if}>##NO_SEARCH_ENGINE##</option>
				<option value="private" {if ((($myNetCVGroup != FALSE) && ($myNetCVGroup->getInfo("diffusion") == "private")) || ($currentGroupInfo.diffusion == "public"))}SELECTED{/if}>##PRIVATE_DIFFUSION##</option>
			</select>
			<br /><br />
			<strong>##EMAIL_DISPLAY##</strong>
			<br />
			<select name="netcvGroupEmailDisplay">
				<option value="form" {if ((($myNetCVGroup != FALSE) && ($myNetCVGroup->getInfo("emailDisplay") == "form")) || ($currentGroupInfo.emaildisplay == "form"))}SELECTED{/if}>##CONTACT_FORM##</option>
				<option value="show" {if ((($myNetCVGroup != FALSE) && ($myNetCVGroup->getInfo("emailDisplay") == "show")) || ($currentGroupInfo.emaildisplay == "show"))}SELECTED{/if}>##DISPLAY_EMAIL_ADDRESS##</option>
			</select>
			<br /><br />
			<strong>##DESIGN_CHOICE##</strong>
			<br />
			<div class="skins">
				<select name="netcvGroupSkin">
					{section name=i loop=$myNetCVSkinList step=1}
						{assign var="mySkin" value=$myNetCVSkinList[i]}
						{*Gestion de l'affichage des categories... Je suis preneur s'il y a mieux*}
						{if ($category != $mySkin->getInfo("category"))}
							<option class="category" value="{$mySkin->getInfo("id")}">{$mySkin->getInfo("category")}</option>
							{assign var="category" value=$mySkin->getInfo("category")}
						{/if}
					<option value="{$mySkin->getInfo("id")}" {if ((($myNetCVGroup != FALSE) && ($myNetCVGroup->getInfo("skin_id") == $mySkin->getInfo("id"))) || ($currentGroupInfo.design == $mySkin->getInfo("id")))}SELECTED{/if}>&nbsp;&nbsp;{$mySkin->getInfo("name")}</option>
					{/section}
				</select>
			</div>
		</div>
		<br /><br />
		<div class="formAddTextButton">
			<input type="submit" value="{$netcvFormText}" name="post">
			(<a href="{kurl page=""}">##BACK##</a>)
		</div>
	</form>
</div>
