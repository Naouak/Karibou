{section name=m loop=$netcvMessages step=1}
	{if ($netcvMessages[m].0 < 2)}
	<div class="success">
		{$netcvMessages[m].1}
	</div>
	{else}
	<div class="error">
		{$netcvMessages[m].1}
	</div>
	{/if}
{/section}