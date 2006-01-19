<h1>##DATAVIEW##</h1>
<h2>##DATAVIEW_DEFAULT##</h2>

{if ($sources|@count > 0)}
<ul>
	{foreach from=$sources item=source}
	<li><a href="{kurl page="list" source=$source->getTableName()}">{$source->getTableName()}</a></li>
	{/foreach}
</ul>
{else}
##DV_NODATASOURCE##
{/if}