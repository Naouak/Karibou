<h3>Gmail Config</h3>
{*<form action="{kurl action="post"}" method="post">*}
##LOGIN## <input type="text" name="gmaillogin" value="{$gmaillogin}" /><br />
##PASSWORD## <input type="password" name="gmailpass" value="{$gmailpass}" /><br />
##NUMOFELEMENTS##
<select name="gmailmax">
	<option value="3" {if $max==3} SELECTED{/if}>3</option>
	<option value="5" {if $max==5} SELECTED{/if}>5</option>
	<option value="7" {if $max==7} SELECTED{/if}>7</option>
	<option value="9" {if $max==9} SELECTED{/if}>9</option>
</select>
{*<input type="submit" value="##SUBMIT##" />*}
{*</form>*}