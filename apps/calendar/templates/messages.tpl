{section name=m loop=$calendarMessages step=1}
	{if ($calendarMessages[m].0 < 2)}
	<div class="success">
		{$calendarMessages[m].1}
	</div>
	{else}
	<div class="error">
		{$calendarMessages[m].1}
	</div>
	{/if}
{/section}