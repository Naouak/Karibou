	{if ($records|@count >0)}
	<table class="list" cellpadding="0" cellspacing="0">
		<tr>
			<th>&nbsp;</th>
			{foreach from=$listfields item=listfield}
			<th>{$listfield}</th>
			{/foreach}
		</tr>
		{foreach from=$records item=record}
		<tr class="{cycle values="one,two"}">
			<td class="detailslink"><a href="{kurl page="details" source=$source->getTableName() recordid=$record.id}" title="##DV_DISPLAYDETAILS##"><span>##DV_DETAILS##</span></a></td>
			{foreach from=$listfields item=listfield}
			<td>{$record.$listfield|escape:"html"}&nbsp;</td>
			{/foreach}
		</tr>
		{/foreach}
	</table>
	{else}
	##DV_NODATASOURCE##
	{/if}