<div id="mc2" class="mc2">
	{if !$invert}<ul id="messages"></ul>{/if}

	<form id="msg_form" action="{kurl action="post"}" method="post">
		<input type="text" name="msg" />
	</form>

	{if $invert}<ul id="messages"></ul>{/if}
</div>
