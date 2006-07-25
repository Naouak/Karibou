{if $pubkey_exp}
<script language="JavaScript" src="/themes/js/BigInt.js"></script>
<script language="JavaScript" src="/themes/js/Barrett.js"></script>
<script language="JavaScript" src="/themes/js/RSA.js"></script>
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
<h3>
	##KEYCHAIN_TITLE##
{if $unlocked}
	(##UNLOCKED##)
{else}
	(##LOCKED##)
{/if}
</h3>
{if $unlocked}

<p>##KEYCHAINUNLOCK##</p>

<form action="{kurl action='config'}" method="post" >
<fieldset>
	<legend>##FORMRESETKEYCHAIN##</legend>
	<label for="oldpass">##OLDPASSPHRASE## :</label>
		<input type="password" id="oldpass" name="oldpass" /><br class="spacer"/>
	<label for="newpass1">##NEWPASSPHRASE## :</label>
		<input type="password" id="newpass1" name="newpass1" /><br class="spacer"/>
	<label for="newpass2">##RENEWPASSPHRASE## :</label>
		<input type="password" id="newpass2" name="newpass2" /><br class="spacer"/>
{if $pubkey_exp}
	<input type="hidden" name="_crypt" value="true" />
	<input type="submit" name="relock" onclick="crypt('oldpass'); crypt('newpass1'); crypt('newpass2');" value="Re-Lock" />
{else}
	<input type="submit" name="relock" value="Re-Lock" />
{/if}
	<br class="spacer"/>
</fieldset>
</form>

{/if}
{if $oldlock}

<p>##KEYCHAINLOCKED##</p>

<form action="{kurl action='config'}" method="post" >
<fieldset>
	<legend>##FORMUNLOCKKEYCHAIN##</legend>
	<label for="pass">##PASSPHRASE## :</label>
		<input type="password" id="pass" name="pass" /><br class="spacer"/>
{if $pubkey_exp}
	<input type="hidden" name="_crypt" value="true" />
	<input type="submit" name="unlock" onclick="crypt('pass');" value="Unlock" />
{else}
	<input type="submit" name="unlock" value="Unlock" />
{/if}
	<br class="spacer"/>
</fieldset>
</form>

<form action="{kurl action='config'}" method="post" >
<fieldset>
	<legend>##FORMRESETKEYCHAIN##</legend>
	<label for="oldpass">##OLDPASSPHRASE## :</label>
		<input type="password" id="oldpass" name="oldpass" /><br class="spacer"/>
	<label for="newpass1">##NEWPASSPHRASE## :</label>
		<input type="password" id="newpass1" name="newpass1" /><br class="spacer"/>
	<label for="newpass2">##RENEWPASSPHRASE## :</label>
		<input type="password" id="newpass2" name="newpass2" /><br class="spacer"/>
{if $pubkey_exp}
	<input type="hidden" name="_crypt" value="true" />
	<input type="submit" name="relock" onclick="crypt('oldpass'); crypt('newpass1'); crypt('newpass2');" value="Re-Lock" />
{else}
	<input type="submit" name="relock" value="Re-Lock" />
{/if}
	<br class="spacer"/>
</fieldset>
</form>
{/if}

<form action="{kurl action='config'}" method="post" >
<fieldset>
	<legend>##FORMCREATEKEYCHAIN##</legend>
	<label for="pass1">##PASSPHRASE## :</label>
		<input type="password" id="pass1" name="pass1" /><br class="spacer"/>
	<label for="pass2">##REPASSPHRASE## :</label>
		<input type="password" id="pass2" name="pass2" /><br class="spacer"/>
{if $pubkey_exp}
	<input type="hidden" name="_crypt" value="true" />
	<input type="submit" name="create" onclick="crypt('pass1'); crypt('pass2');" value="Create" />
{else}
	<input type="submit" name="create" value="Create" />
{/if}
	<br class="spacer"/>
</fieldset>
</form>
