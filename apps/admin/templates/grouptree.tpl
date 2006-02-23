
<ul id="group-{$key}">
{foreach item=group from=$grouptree}
	<li>
		<a href="{kurl page='editgroup' group=$group.item->getId()}">{$group.item->getName()}</a>
{if $group.childs}
{include file="grouptree.tpl" grouptree=$group.childs key=$group.item->getId()}
{/if}
	</li>
{/foreach}
</ul>

<script type="text/javascript" language="javascript">
// <![CDATA[
	KSortable.create("group-{$key}" ,
		{ldelim}
			dropOnEmpty:true,
			tag:'li',
			overlap:'horizontal',
			constraint: false
		{rdelim}
		) ;
// ]]>
</script>
