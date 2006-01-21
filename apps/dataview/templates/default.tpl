<h1>##DATAVIEW##</h1>
<div class="helper">##DATAVIEW_DEFAULT##</div>
<div class="dataview">
	{if ($sources|@count > 0)}
	<ul class="sources">
		{foreach from=$sources item=source}
		<li><a href="{kurl page="list" source=$source->getTableName()}"><span>{$source->getTableName()}</span></a></li>
		{/foreach}
	</ul>
	{else}
	##DV_NODATASOURCE##
	{/if}
</div>