<h1>##DATAVIEW##</h1>
<div class="helper">##DATAVIEW_DETAILS##</div>
<div class="dataview">
	{if isset($record)}
		{include file="_detailsnavi.tpl"}
		<div class="record">
		{foreach from=$publicfields item=publicfield}
			{if $record.$publicfield != ""}
			<div class="field public {$publicfield}">
				<label for="{$publicfield}">{$publicfield}</label> <span name="{$publicfield}">{$record.$publicfield}&nbsp;</span>
			</div>
			{/if}
		{/foreach}
		{foreach from=$privatefields item=privatefield}
			{if $record.$privatefield != ""}
			<div class="field private {$privatefield}">
				<label for="{$privatefield}">{$privatefield}</label> <span name="{$privatefield}">{$record.$privatefield}&nbsp;</span>
			</div>
			{/if}
		{/foreach}
		</div>
	{/if}
</div>