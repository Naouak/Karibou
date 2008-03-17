	<div id="flashmail_headerbox_sentence" onclick="new Effect.toggle('flashmail_headerbox_unreadlist')" class="{if $flashmails|@count == 0}dontshow{else}show{/if}">
		<script>alert("test");</script>
		<div>##YOU_HAVE## {$flashmails|@count} {if ($flashmails|@count) > 1}##NEW_FLASHMAILS##{else}##NEW_FLASHMAIL##{/if}</div>
	</div>

	<div id="flashmail_headerbox_unreadlist" class="flashmail dontshow">
	{if $flashmails|@count > 0}
		{include file="list.tpl"}
	{/if}
	</div>
