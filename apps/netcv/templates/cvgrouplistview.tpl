{assign var="myNetCVGroupsObjects" value=$myNetCVGroupList->returnGroupsObjects()}

{include file="messages.tpl"}

<div class="helper">
	##HELPER_CLICK_ON_LANG_TO_EDIT##
</div>

<div class="groupList">
	<h2>##MY_CV##</h2>

{if ($myNetCVGroupsObjects|@count > 0) }
    {section name=i loop=$myNetCVGroupsObjects step=1}
    	{assign var="aGroup" value=$myNetCVGroupsObjects[i]}
    	{assign var="aGroupId" value=$aGroup->getInfo("id")}
    	<div class="aGroup">
			<div class="toolkit">
				<a href="{kurl page="cvgroupmodify" gid=$aGroupId}">##EDIT##</a> - <a href="{kurl page="cvgroupdelete" gid=$aGroupId}" onclick="return confirm('##ARE_YOU_SURE##');">##DELETE##</a>
			</div>
			<div class="infos">
				<h3>{$aGroup->getInfo("name")} @ <a href="http://{$aGroup->getInfo("hostname")}.{$config.intranet.host}">http://{$aGroup->getInfo("hostname")}.{$config.intranet.host}</a></h3>
				{assign var="aCVList" value=$aGroup->returnCVList()}
				<ul>
				{section name=j loop=$aCVList step=1}
					{assign var="aCV" value=$aCVList[j]}
					<li>
							&nbsp;<strong><a href="{kurl page="cvsectionlist" cvid=$aCV->getInfo("id") gid=$aGroupId}">{$myNetCVLanguage->getNameByCode($aCV->getInfo("lang"))}</a></strong> (<a href="{kurl page="cvsinglecvmodify" gid=$aGroupId cvid=$aCV->getInfo("id")}">##CHANGE_LANGUAGE##</a> - <a href="{kurl page="cv" hostname=$aGroup->getInfo("hostname") lang=$aCV->getInfo("lang") preview=1}">##PREVIEW##</a> - <a href="{kurl page="personalinfo" cvid=$aCV->getInfo("id") gid=$aGroupId}">##PERSOINFO##</a> - <a href="{kurl page="cvsinglecvdelete" cvid=$aCV->getInfo("id") gid=$aGroupId}" onclick="return confirm('##ARE_YOU_SURE##');">##DELETE_CV##</a>)
					</li>
    			{/section}
    			</ul>
				<div class="add">
					+ <a href="{kurl page="cvsinglecvmodify" gid="$aGroupId"}">##ADD_TRANSLATION_TO_CV##</a>
				</div>
			</div>
		</div>
    {/section}
	<div class="add">
    + <a href="{kurl page="cvgroupmodify"}">##CREATE_ANOTHER_CV##</a>
	</div>
{else}
	##NO_CV##
	<div class="add">
		+ <a href="{kurl page="cvgroupmodify"}"><strong>##CREATE_FIRST_CV##</strong></a>
	</div>
{/if}
</div>
