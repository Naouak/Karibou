	{if $flashmails|@count > 0}
	<li id="account_flashmail_headerbox_sentence">
		<a href="#" onclick="flashmail_blinddown('flashmail_headerbox_unreadlist')">{$flashmails|@count} {if ($flashmails|@count) > 1}##NEW_FLASHMAILS##{else}##NEW_FLASHMAIL##{/if}</a>
	</li>
	{/if}