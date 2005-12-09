{include file="menumail.tpl"}
<form action="{kurl action='send'}" method="post" >
<div class="headbox">
	<label for="from">From :</label>
{if $from_table}
		<select type="text" id="from" name="from">
{foreach item=from from=$from_table}
			<option value="{$from.email}">{$from.email}</option>
{/foreach}
		</select><br class="spacer"/>
{else}
		<input type="text" id="from" name="from"/><br class="spacer"/>
{/if}
	<label for="to">To :</label>
		<textarea autocomplete="off" type="text" id="to" name="to">{$to}</textarea>
		<div class="autocompleter" id="to_choices"></div>
		<br class="spacer"/>
	<label autocomplete="off" for="cc">CC :</label>
		<textarea type="text" id="cc" name="cc">{$cc}</textarea>
		<div class="autocompleter" id="cc_choices"></div>
		<br class="spacer"/>
	<label for="subject">Subject :</label>
		<input type="text" id="subject" name="subject" value="{$subject}"/><br class="spacer"/>
</div>

<script>
new Ajax.Autocompleter("to", "to_choices",
	"{kurl app='addressbook' page='auto' postname='to' }", 
	{ldelim}
		tokens:','
	{rdelim}
);
new Ajax.Autocompleter("cc", "cc_choices",
	"{kurl app='addressbook' page='auto' postname='cc' }", 
	{ldelim}
		tokens:','
	{rdelim}
);
</script>


<div class="bodybox">
<textarea name="body">
{if $form}
{$from} ##WROTE## :
{/if}



{$body}
</textarea>
</div>
<div class="headbox">
	<input type="submit" class="button" value="Send" /><br class="spacer"/>
</div>
</form>