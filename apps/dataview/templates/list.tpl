<h1>##DATAVIEW##</h1>
<h2>##DATAVIEW_DETAILS##</h2>
<div class="dataview">

	<div class="navigation">
		<span class="nbrecords">{$nbrecords} ##RECORDS##</span>
		{if ($pagenum > 1) }
		<a href="{kurl page="list" source=$source->getTableName() pagenum=$pagenum-1}" class="previous">##PREVIOUS##</a>
		{/if}
		
		{if ($pagenum*$maxlines < $nbrecords) }
		<a href="{kurl page="list" source=$source->getTableName() pagenum=$pagenum+1}" class="next">##NEXT##</a>
		{/if}
		<span class="viewingrecords">
			##VIEWING_RECORDS## 
				{$pagenum*$maxlines-$maxlines} 
			##TO## 
				{if ($pagenum*$maxlines) > $nbrecords}{$nbrecords}{else}{$pagenum*$maxlines}{/if}
		</span>
	</div>
	
	
	{if ($records|@count >0)}
	<table>
		<tr>
			<th>&nbsp;</th>
			{foreach from=$publicfields item=publicfield}
			<th>{$publicfield}</th>
			{/foreach}
		</tr>
		{foreach from=$records item=record}
		<tr>
			<td><a href="{kurl page="details" source=$source->getTableName() recordid=$record.id}">##DETAILS##</a></td>
			{foreach from=$publicfields item=publicfield}
			<td>{$record.$publicfield|escape:"html"}</td>
			{/foreach}
		</tr>
		{/foreach}
	</table>
	{else}
	##DV_NODATASOURCE##
	{/if}

</div>