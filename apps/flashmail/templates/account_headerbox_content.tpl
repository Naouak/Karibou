	<li id="account_flashmail_headerbox_sentence" class="flashmail">
	{if $flashmails|@count > 0}
		<a id="account_flashmail_read_link" href="{kurl app="flashmail" page="unreadlist"}" onclick="flashmail_blinddown('flashmail_headerbox_unreadlist'); return false;">{$flashmails|@count} {if ($flashmails|@count) > 1}##NEW_FLASHMAILS##{else}##NEW_FLASHMAIL##{/if}</a>
	{/if}
	</li>
	
	<div id="flashmail_headerbox_unreadlist_TMP" style="visibility: hidden; display: none;">
	{if $flashmails|@count > 0}
		{include file="list.tpl"}
	{/if}
	</div>
