{foreach item=group from=$grouptree}
	<li><a href="{kurl page="groupid" id=$group.item->getId()}">{$group.item->getName()}</a></li>
{if $group.childs}
	<ul>
{include file="grouptree.tpl" grouptree=$group.childs}
	</ul>
{/if}
{/foreach}

