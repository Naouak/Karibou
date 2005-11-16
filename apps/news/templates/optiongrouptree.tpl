{foreach item=group from=$grouptree}
		<option value="{$group.item->getId()}" {if (isset($theNewsToModify) && ($theNewsToModify->getGroup() == $group.item->getId()))}SELECTED{/if}>{$group.item->getName()|indent:$level:'&nbsp;&nbsp;&nbsp;'}</option>
	{if $group.childs}
		{include file="optiongrouptree.tpl" grouptree=$group.childs level=$level+1}
	{/if}
{/foreach}