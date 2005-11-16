{assign var="mySections" value=$myNetCV->child}
<div class="netcv">
	{include file="applinks.tpl"}
	
	<div class="breadcrumbs">
		##YOU_ARE_HERE##: <a href="{kurl app="netcv"}">##HOME##</a> :: <a href="{kurl app="netcv"}">CV &quot;{$myNetCVGroup->getInfo("name")}&quot;</a> :: <a href="{kurl app="netcv" page="cvsectionlist" cvid=$cvid gid=$gid}">{$myNetCVLanguage->getNameByCode($myNetCVSingleCV->getInfo("lang"))}</a>
	</div>

	{if ($mySections|@count > 0)}
	<div class="helper">
		##HELPER_SECTIONS##
	</div>
	{/if}

{include file="messages.tpl"}

	<div class="sectionList">
		<h2>##SECTION##s</h2>

		{if ($mySections|@count > 0)}
			{section name=i loop=$mySections step=1}
				{assign var="aSection" value=$mySections[i]}
				<div class="aSection">
					<div class="toolkit">
						<a href="{kurl page="cvsectionmodify" id=$aSection->id cvid=$aSection->resume_id  gid=$gid}">##RENAME##</a> - <a href="{kurl page="cvelementdelete" id=$aSection->id cvid=$aSection->resume_id gid=$gid}">##DELETE##</a> - <a href="{kurl page="cvelementmove" id=$aSection->id direction="up" cvid=$aSection->resume_id gid=$gid}">##MOVE_UP##</a> - <a href="{kurl page="cvelementmove" id=$aSection->id direction="down" cvid=$aSection->resume_id  gid=$gid}">##MOVE_DOWN##</a>
					</div>
					<a href="{kurl page="cvitemlist" pid=$aSection->id cvid=$aSection->resume_id  gid=$gid}"><strong>{$aSection->infos}</strong></a>
					(<em>{$aSection->child|@count} ##ELEMENT##{if (($aSection->child|@count)>1)}s{/if}</em>)
				</div>
			{/section}
			<div class="add">
				<a href="{kurl page="cvsectionmodify" cvid=$cvid gid=$gid}"><strong>##ADD_A_SECTION##</strong></a>
			</div>
		{else}
			<div class="empty">
				##NO_SECTION##
			</div>
			<div class="add">
				<a href="{kurl page="cvsectionmodify" cvid=$cvid first=1 gid=$gid}" >##ADD_FIRST_SECTION##</a>
			</div>
		{/if}
	</div>

</div>
