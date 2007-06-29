{foreach item=group from=$grouptree}
	<li>
		<a href="{kurl page='linkusers2group'}?group={$group.item->getId()}">{$group.item->getName()}</a>
	</li>
{if $group.childs}
<ul>
{include file="linkusers2grouptree.tpl" grouptree=$group.childs}
</ul>
{/if}
{/foreach}