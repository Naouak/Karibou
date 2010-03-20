<div class="mc2">
	{if !$invert}<ul id="messages"></ul>{/if}

	<form class="mc2_form" id="msg_form" action="{kurl action="post"}" method="post">
		<div class="mc2_input_submit"{if !$button} style="display: none;"{/if}>
			<input type="submit" value="{t}Envoyer{/t}" id="input_submit" />
		</div>
		<div class="mc2_input_text">
			<input type="text" name="msg" id="input_text"{if !$button} class="mc2_input_text_noradius"{/if} />
		</div>
	</form>

	{if $invert}<ul id="messages"></ul>{/if}
</div>
