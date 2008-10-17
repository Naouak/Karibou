<h1>##FLASHMAIL_TITLE##</h1>
<h2>##FLASHMAIL_DESCRIPTION##</h2>
<div class="flashmail">
	<ul>
	{foreach item=flashmail from=$flashmails}
		{if $flashmail->isArchive()}
		<li class="archive">
		{elseif !$flashmail->isRead()}
		<li class="unread">
		{elseif $flashmail->isRead()}
		<li class="read">
		{/if}
			<span id="account_flashmail_read_link" class="readlink"><a href="#" onclick="FlashmailManager.Instance.markAsRead({$flashmail->getId()}); return false;">##SETASREAD##</a></span>
			<span class="time"><acronym title="{$flashmail->getInfo("date")|date_format:"%A %d %B %Y @ %H:%M.%S"}">{$flashmail->getInfo("date")|date_format:"%H:%M"}</acronym></span>
			<span class="author"><a href="{kurl app='annuaire' username=$flashmail->getAuthorLogin()}">{$flashmail->getAuthorDisplayName()}</a></span>
			<div class="message">{$flashmail->getMessage()}</div>
		</li>
	{/foreach}
	</ul>
</div>
