{foreach from=$log item=entry}
	{$entry.rev} - {$entry.author} :<br />
	{$entry.msg}
	<hr />
{/foreach}
