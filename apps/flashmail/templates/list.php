	<ul>
	{foreach item=flashmail from=$flashmails}
		{if $flashmail->isArchive()}
		<li class="archive">
		{elseif !$flashmail->isRead()}
		<li class="unread">
		{elseif $flashmail->isRead()}
		<li class="read">
		{/if}
			<span class="answerlink"><a href="{kurl app="flashmail" page="write" flashmailid=$flashmail->getId()}" onclick="new Ajax.Request('{kurl app="flashmail" page="setasread" flashmailid=$flashmail->getId()}', {literal}{asynchronous:true, evalScripts:true}{/literal}); flashmail_headerbox_update(); new Ajax.Updater('flashmail_headerbox_answer', '{kurl app="flashmail" page="headerbox_answer" flashmailid=$flashmail->getId()}',{literal} {asynchronous:true, evalScripts:true}{/literal}); Effect.Appear('flashmail_headerbox_answer'); stop_blink();return false;">##REPLY##</a></span>
			<span class="answerlink"><a href="{kurl app="flashmail" page="setasread" flashmailid=$flashmail->getId()}" onclick="new Ajax.Request('{kurl app="flashmail" page="setasread" flashmailid=$flashmail->getId()}', {literal}{asynchronous:true, evalScripts:true}{/literal}); flashmail_headerbox_update(); return false;">##SETASREAD##</a></span>
			<span class="time"><acronym title="{$flashmail->getInfo("date")|date_format:"%A %d %B %Y @ %H:%M.%S"}">{$flashmail->getInfo("date")|date_format:"%H:%M"}</acronym></span>
			<span class="author"><a href="{kurl app='annuaire' username=$flashmail->getAuthorLogin()}">{$flashmail->getAuthorDisplayName()}</a></span>
			<div class="message">{$flashmail->getMessage()}</div>
		</li>
	{/foreach}
	</ul>
