	<div class="navigation">
		<div class="links">
		{if ($pagenum > 1) }
			<a href="{kurl page="list" source=$source->getTableName() pagenum=$pagenum-1}" class="previous">##DV_PREVIOUS##</a>
		{else}
			<span class="previous">&nbsp;</span>
		{/if}
		{if ($pagenum*$maxlines < $nbrecords) }
			<a href="{kurl page="list" source=$source->getTableName() pagenum=$pagenum+1}" class="next">##DV_NEXT##</a>
		{else}
			<span class="next">&nbsp;</span>
		{/if}
		</div>

		<div class="nbrecords">{$nbrecords} ##DV_RECORDS##</div>

		<div class="viewingrecords">
			##DV_VIEWINGRECORDS## 
				<strong>{$pagenum*$maxlines-$maxlines}</strong>
			##DV_TO## 
				<strong>{if ($pagenum*$maxlines) > $nbrecords}{$nbrecords}{else}{$pagenum*$maxlines}{/if}</strong>
		</div>
	</div>