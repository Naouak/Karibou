<h1>##DATAVIEW##</h1>
<h2>##DATAVIEW_DETAILS##</h2>
<div class="dataview">
	{if isset($record)}
		<div class="record">
		{foreach from=$publicfields item=publicfield}
			<div class="public">
				<label for="{$publicfield}">{$publicfield}</label> <span name="{$publicfield}">{$record.$publicfield}</span>
			</div>
		{/foreach}
		{foreach from=$privatefields item=privatefield}
			<div class="private">
				<label for="{$privatefield}">{$privatefield}</label> <span name="{$privatefield}">{$record.$privatefield}</span>
			</div>
		{/foreach}
		</div>
	{/if}
</div>