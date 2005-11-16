	<ul>
	{foreach item=flashmail from=$flashmails}
		{if $flashmail->isArchive()}
		<li class="archive">
		{elseif !$flashmail->isRead()}
		<li class="unread">
		{elseif $flashmail->isRead()}
		<li class="read">
		{/if}
			<span class="time"><acronym title="{$flashmail->getInfo("date")|date_format:"%A %d %B %Y @ %H:%M.%S"}">{$flashmail->getInfo("date")|date_format:"%H:%M"}</acronym></span>
			<span class="author"><a href="{kurl app='annuaire' username=$flashmail->getAuthorLogin()}">{$flashmail->getAuthorSurname()}</a></span>
			<span class="message">{$flashmail->getMessage()}</span>
		</li>
	{/foreach}
	</ul>