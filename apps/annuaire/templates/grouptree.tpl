{foreach item=group from=$grouptree}
	<li><a href="{kurl page="groupid" id=$group.item->getId()}" title="{$group.item->getName()}{if ($group.item->getDescription() !== FALSE) && ($group.item->getDescription() != "")} : {$group.item->getDescription()}{/if}">{$group.item->getName()}</a></li>
{if $group.childs}
	<ul>
{include file="grouptree.tpl" grouptree=$group.childs}
	</ul>
{/if}
{/foreach}