<h1>##DATAVIEW##</h1>
<h2>##DATAVIEW_DETAILS##</h2>

{if ($sources|@count >0)}
<ul>
	{foreach from=$sources item=source}
	<li>{$source->getName()}</li>
	{/foreach}
</ul>
{else}
##DV_NODATASOURCE##
{/if}