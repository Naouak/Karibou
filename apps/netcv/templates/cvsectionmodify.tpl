<div class="netcv">
	{assign var="mySection" value=$myNetCV->returnChildById($id)}
	{assign var="mySectionInfos" value=$mySection->infos}
	
	{if ($mySection != FALSE)}
		{*La section existe*}
		{assign var="netcvPageTitle" value="##SECTION_NAME_MODIFY##: $mySectionInfos"}
		{assign var="netcvFormTextToSectionList" value="##APPLY_MODIFICATION_AND_BACK_TO_SECTIONS##"}
		{assign var="netcvFormTextToSection" value="##APPLY_MODIFICATION_AND_ENTER_SECTION##"}
	{else}
		{*La section n existe pas et on va la creer*}
		{assign var="netcvPageTitle" value="##SECTION_CREATION##"}
		{assign var="netcvFormTextToSectionList" value="##CREATE_AND_BACK_TO_SECTIONS##"}
		{assign var="netcvFormTextToSection" value="##CREATE_AND_GO_INTO##"}
	{/if}

	<div class="breadcrumbs">
		##YOU_ARE_HERE##: <a href="{kurl page=""}">##HOMEPAGE##</a> :: <a href="{kurl page=""}">CV &quot;{$myNetCVGroup->getInfo("name")}&quot;</a> :: <a href="{kurl app="netcv" page="cvsectionlist" cvid=$cvid gid=$gid}">{$myNetCVLanguage->getNameByCode($myNetCVSingleCV->getInfo("lang"))}</a> :: {if $mySection != FALSE}<a href="{kurl page="cvitemlist" cvid=$cvid gid=$gid pid=$id}">{$mySection->infos}</a>{else}##SECTION_CREATION##{/if}
	</div>

	
	<h1>{$netcvPageTitle}</h1>
	
	{if ($first == "1")}
	<div class="helper">
		##HELPER_SECTION_CREATION##
	</div>
	{/if}
	
	<form action="{kurl page="cvsectionsave"}" method="post">
	  <input type="hidden" name="netcvSectionId" value="{$id}">
	  <input type="hidden" name="netcvSingleCVId" value="{$cvid}">
	  <input type="hidden" name="netcvGroupId" value="{$gid}">
	  <div class="Level1TitleAdd">
	    <b>##SECTION_TITLE##</b>
			<br />
			<input type="text" name="netcvSectionName" size="30" value="{$mySection->infos|escape:"html"}">
		  <br />
			<div class="ie">
			 <em>##SECTION_TITLE_HELP##</em>
			</div>
	  </div>
		<br />
	  <div class="formAddTextButton">
	    <input type="submit" value="{$netcvFormTextToSectionList}" name="netcvToSectionList">
  	    <input type="submit" value="{$netcvFormTextToSection}" name="netcvToSection">
	    (<a href="{kurl page="cvsectionlist" cvid="$cvid" gid=$gid}">##CANCEL##</a>)
	  </div>
	</form>
</div>
