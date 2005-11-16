{foreach item=group from=$grouptree}
	<li>
		<label for="input_group{$group.item->getId()}">{$group.item->getName()}</label>
		<input id="input_group{$group.item->getId()}" type="checkbox" name="group{$group.item->getId()}"
			value="{$group.item->getId()}"{if $group.item->checked} CHECKED{/if} />
	</li>
{if $group.childs}
<ul>
{include file="usergrouptree.tpl" grouptree=$group.childs}
</ul>
{/if}
{/foreach}