{section name=m loop=$theNewsMessages step=1}
	{if ($theNewsMessages[m].0 < 2)}
	<div class="success">
		{$theNewsMessages[m].1}
	</div>
	{else}
	<div class="error">
		{$theNewsMessages[m].1}
	</div>
	{/if}
{/section}