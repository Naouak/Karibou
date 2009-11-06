{if $pubkey_exp}
<script>
var key = new RSAKeyPair(
	"{$pubkey_exp}",
	"",
	"{$pubkey_mod}"
);

function crypt(id)
{ldelim}
	var item = document.getElementById(id);
	var txt = item.value;
	item.value = encryptedString2(key, txt);
	return true;
{rdelim}
</script>
{/if}
<h1>##WEBMAIL_TITLE## ##FOR## {$username}</h1>
<p><a href="{kurl page=compose}">##COMPOSELINK##</a></p>
{foreach name=pref key=k item=pref from=$connections}
{if $smarty.foreach.pref.first}
<p>##SAVEDSERVER##</p>
<ul>
{/if}
	<li>
		<a href="{kurl page=mbox serv=$k mailbox=INBOX}">{$pref.host} : {$pref.login}</a>
	</li>
{if $smarty.foreach.pref.last}
</ul>
{/if}
{/foreach}
<br />
<br />
<form action="{kurl action='connect' }" method="POST">
	<label for="mail_host">Hostname : </label>
	<input id="mail_host" type="text" name="host" value="{$host}" />
	<label for="mail_ssl">ssl : </label>
	<input id="mail_ssl" type="checkbox" name="ssl" value="true" /><br />
	<label for="mail_login">Login : </label><input id="mail_login" type="text" name="login" value="{$login}" /><br />
	<label for="mail_pass">Password : </label>
	<input id="mail_pass" type="password" name="pass" />
{if $pubkey_exp}
	<input type="hidden" name="_crypt" value="true" />
	<input type="submit" name="mail_save" onclick="crypt('mail_pass');" value="Save & Connect" />
{else}
	<input type="submit" name="mail_save" value="Save & Connect" />
{/if}
</form>
