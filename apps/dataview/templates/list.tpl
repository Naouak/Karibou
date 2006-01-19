<h1>##DATAVIEW##</h1>
<h2>##DATAVIEW_DETAILS##</h2>

{if ($records|@count >0)}
<table>
	{foreach from=$records item=record}
	<tr>
		<td>{$record}</td>
	</tr>
	{/foreach}
</table>
{else}
##DV_NODATASOURCE##
{/if}