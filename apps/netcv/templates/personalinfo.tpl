{assign var="myNetCVGroupsObjects" value=$myNetCVGroupList->returnGroupsObjects()}



<div class="netcv">
{include file="applinks.tpl"}
{if (!isset($gid, $cvid) || $gid == FALSE || $cvid == FALSE)}
	{assign var="pageTitle" value="##DEFAULT_PERSONAL_INFO##"}
	{assign var="myObject" value=$myNetCVUser}
{else}
	{assign var="pageTitle" value="##CV_PERSONAL_INFO##"}
	{assign var="myObject" value=$myNetCVSingleCV}
{/if}
	<h1>{$pageTitle}</h1>
	
{include file="messages.tpl"}
	
	<div class="helper">
{if (!isset($gid, $cvid) || $gid == FALSE || $cvid == FALSE)}
##HELPER_PERSONAL_INFO##
{else}
##HELPER_CV_PERSONAL_INFO##
{/if}
		{$helper}
	</div>
 <div class="personalInfo">
  <div class="tabs">
	<strong> ##INFORMATION## :</strong>
	<ul>
		<li><a href="{kurl page="personalinfo"}">##DEFAULT_PERSONAL_INFO##</a></li>
	{section name=i loop=$myNetCVGroupsObjects step=1}
		{assign var="aGroup" value=$myNetCVGroupsObjects[i]}
		{assign var="aGroupId" value=$aGroup->getInfo("id")}
			{assign var="aCVList" value=$aGroup->returnCVList()}
			<li>
				CV {$aGroup->getInfo("name")} :
				{section name=j loop=$aCVList step=1}
					{assign var="aCV" value=$aCVList[j]}
					<strong><a href="{kurl page="personalinfo" gid=$aGroupId cvid=$aCV->getInfo("id")}">{$myNetCVLanguage->getNameByCode($aCV->getInfo("lang"))}</a></strong>
	   		{/section}
			</li>
	{/section}
	</ul>
  </div>
  <div class="content">
	<form method="post" action="{kurl page="personalinfosave"}">
		<input type="hidden" name="gid" value="{$gid}">
		<input type="hidden" name="cvid" value="{$cvid}">
		<div class="formAddText">
			##FIRSTNAME##:
		  <input type="text" name="firstname" size="15" maxlength="15" value="{$myObject->getInfo("firstname")|escape:"html"}">
		</div>
		<div class="formAddText">
		 	##LASTNAME##:
			<input type="text" name="lastname" size="15" maxlength="15" value="{$myObject->getInfo("lastname")|escape:"html"}">
		</div>
		<div class="formAddText">
			##EMAIL##:
			<input type="text" name="email" size="30" maxlength="50" value="{$myObject->getInfo("email")|escape:"html"}">
		</div>
		<div class="formAddText">
			##ADDRESS##:
			<textarea class="formAddText" name="address1stline" cols="25" rows="4">{$myObject->getInfo("address1stline")|escape:"html"}</textarea>
		</div>
		<div class="formAddText">
			##POSTALCODE##:
		<input type="text" name="addresscitycode" size="6" maxlength="6" value="{$myObject->getInfo("addresscitycode")|escape:"html"}">
		</div>
		<div class="formAddText">
			##CITY##:
		<input type="text" name="addresscity" size="18" maxlength="30" value="{$myObject->getInfo("addresscity")|escape:"html"}">
		</div>
		<div class="formAddText">
			##HOMEPHONE##:
		<input type="text" name="phonehome" size="11" maxlength="20" value="{$myObject->getInfo("phonehome")|escape:"html"}">
		</div>
		<div class="formAddText">
			##COMPANYPHONE##:
		<input type="text" name="phonecompany" size="11" maxlength="20" value="{$myObject->getInfo("phonecompany")|escape:"html"}">
		</div>
		<div class="formAddText">
			##MOBILEPHONE##:
		<input type="text" name="phonemobile" size="11" maxlength="20" value="{$myObject->getInfo("phonemobile")|escape:"html"}">
		</div>
		<div class="formAddText">
			##OTHERINFO##:
			<input type="text" name="otherinfos" size="50" maxlength="100" value="{$myObject->getInfo("otherinfos")|escape:"html"}">
		</div>
		<div class="formAddText">
			<strong>##JOBTITLE##</strong>:
			<input type="text" name="jobtitle" size="50" maxlength="200" value="{$myObject->getInfo("jobtitle")|escape:"html"}">
		</div>
		<div class="formAddTextButton">
			<input type="submit" value="##UPDATE##" name="Update">
		</div>
	</form>
  </div>
 </div>
</div>
