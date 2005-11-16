{foreach item=group from=$grouptree}
	<option value="{$group.item->getId()}">{$group.item->getName()|indent:$level:'--'}</option>
{if $group.childs}
{include file="optiongrouptree.tpl" grouptree=$group.childs level=$level+1}
{/if}
{/foreach}