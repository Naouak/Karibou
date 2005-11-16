{section name=m loop=$formMessages step=1}
	{if ($formMessages[m].0 < 2)}
	<div class="success">
		{$formMessages[m].1}
	</div>
	{else}
	<div class="error">
		{$formMessages[m].1}
	</div>
	{/if}
{/section}