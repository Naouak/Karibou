{include file="menumail.tpl"}
<form action="{kurl action='send'}" method="post" >
<div class="headbox">
	<label for="from">##FROM:##</label>
{if $from_table}
		<select type="text" id="from" name="from">
{foreach item=from from=$from_table}
			<option value="{$from.email}">{$from.email}</option>
{/foreach}
		</select><br class="spacer"/>
{else}
		<input type="text" id="from" name="from"/><br class="spacer"/>
{/if}
	<label for="mail_to">##To:##</label>
		<textarea autocomplete="off" type="text" id="mail_to" name="to">{$to}</textarea>
		<div class="autocompleter" id="to_choices"></div>
		<br class="spacer"/>
	<label for="mail_cc">##CC:##</label>
		<textarea id="mail_cc" name="cc">{$cc}</textarea>
		{*<input autocomplete="off" type="text" id="mail_cc" name="cc" value="{$cc}" />*}
		<div class="autocompleter" id="cc_choices"></div>
		<br class="spacer"/>
	<label for="subject">##SUBJECT:##</label>
		<input type="text" id="subject" name="subject" value="{$subject}"/><br class="spacer"/>
</div>

<script>
new Ajax.Autocompleter("mail_to", "to_choices",
	"{kurl app='addressbook' page='auto' postname='to' }", 
	{ldelim}
		tokens:','
	{rdelim}
);
new Ajax.Autocompleter("mail_cc", "cc_choices",
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
	<input type="submit" class="button" value="Bouh" /><br class="spacer"/>
</div>
</form>
