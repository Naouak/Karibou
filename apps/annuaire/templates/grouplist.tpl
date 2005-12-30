<ul>
{foreach item=group from=$usergroups}
		<li{if !$group.childs} style="font-weight: bold;"{/if}><a href="{kurl page="groupid" id=$group.item->getId()}">{$group.item->getName()}</a></li>
	{if $group.childs}
		{include file="grouplist.tpl" usergroups=$group.childs level=$level+1}
	{/if}
{/foreach}
</ul>