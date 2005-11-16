{assign var="mySection" value=$myNetCV->returnChildById($pid)}
<div class="netcv">
	{include file="applinks.tpl"}
	
	<div class="breadcrumbs">
		##YOU_ARE_HERE##: <a href="{kurl page=""}">##HOME##</a> :: <a href="{kurl page=""}">CV &quot;{$myNetCVGroup->getInfo("name")}&quot;</a> :: <a href="{kurl app="netcv" page="cvsectionlist" cvid=$cvid gid=$gid}">{$myNetCVLanguage->getNameByCode($myNetCVSingleCV->getInfo("lang"))}</a> :: <a href="{kurl page="cvitemlist" pid=$pid cvid=$cvid gid=$gid}">&quot;<em>{$mySection->getVar("infos")}</em>&quot;</a>
	</div>
	
{include file="messages.tpl"}
	
	<div class="helper">
		##HELPER_ITEM##
	</div>
	
	<div class="itemList">
		<h2>##ELEMENTS_OF## &quot;<em>{$mySection->getVar("infos")}</em>&quot;</h2>
		{section name=i loop=$mySection->child step=1}
			{assign var="anItem" value=$mySection->child[i]}
			<div class="anItem">
				<div class="toolkit">
					<a href="{kurl page="cvitemmodify" gid=$gid cvid=$cvid pid=$pid id=$anItem->id}">##EDIT##</a> - <a href="{kurl page="cvelementdelete" id=$anItem->id cvid=$cvid gid=$gid}" onclick="return confirm('##THIS_WILL_DELETE## \'{$anItem->infos}\' !\n##ARE_YOU_SURE##');">##DELETE##</a> - <a href="{kurl page="cvelementmove" id=$anItem->id direction="up" cvid=$cvid gid=$gid}">##MOVE_UP##</a> - <a href="{kurl page="cvelementmove" id=$anItem->id direction="down" cvid=$cvid gid=$gid}">##MOVE_DOWN##</a>
				</div>
				<div class="infos">
					<div class="title">
						{$anItem->infos}
					</div>
					<div class="subtitle">
					{if isset($anItem->child[0])}
						{$anItem->child[0]->infos}
					{/if}
					</div>
					<div class="description">
					{if isset($anItem->child[0], $anItem->child[0]->child[0])}
						{$anItem->child[0]->child[0]->getVarXHTML("infos")}
					{/if}
					</div>
				</div>
		    </div>
		{/section}
		<div class="add">
			<a href="{kurl page="cvitemmodify" pid=$mySection->getVar("id") cvid=$cvid gid=$gid}"><strong>##ADD_AN_ELEMENT##</strong></a>
		</div>
	</div>
</div>
