<div class="mc2">
	{if !$invert}<ul id="messages"></ul>{/if}

	<form class="mc2_form" id="msg_form" action="{kurl action="post"}" method="post">
		<div class="mc2_input_submit">
			<input type="submit" value="{t}Envoyer{/t}" />
		</div>
		<div class="mc2_input_text">
			<input type="text" name="msg" />
		</div>
	</form>

	{if $invert}<ul id="messages"></ul>{/if}
</div>
