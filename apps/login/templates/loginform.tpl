{if $isLoggedIn}
<div class="login">
	<div class="logout"><a href="{kurl page="logout"}">##LOGOUT##</a></div>
	<div class="editprofile"><a href="{kurl app="annuaire" username=$username act='edit'}">##EDITPROFILE##</a></div>
	<div class="configureemailfw"><a href="{kurl app="emailforward"}">##CONFIGURE_EMAILFW##</a></div>
	<div class="changepassword"><a href="{kurl app="changepassword"}">##CHANGEPASSWORD##</a></div>
</div>
{*	
	{if $unlocked}
	<p>##KEYCHAINREADY## <a href="{kurl app='keychain'}">##KEYCHAINLINK##</a>.</p>
	{elseif $oldlock}
	<p>##KEYCHAINLOCKED## <a href="{kurl app='keychain'}">##KEYCHAINLINK##</a> .</p>
	{elseif $nokeychain}
	<p>##NOKEYCHAIN## <a href="{kurl app='keychain'}">##KEYCHAINLINK##</a>.</p>
	{/if}
*}
{else}

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

{literal}
function cryptPass()
{
	var pass = document.getElementById("_pass");
	var crypt = document.getElementById("_crypt");
	var pass_txt = pass.value;
	pass.value = "**";
	crypt.value = encryptedString2(key, pass_txt);
	return true;
}
{/literal}
</script>
{/if}
<h3>##TITLE_AUTH##</h3>
<div class="login">
{section name=m loop=$loginMessages step=1}
	<div class="error">
		{$loginMessages[m].1}
	</div>
{/section}
	<form action="{kurl app="login"}" method="post">
		<label for="_user" class="username">##USERNAME##</label> <input id="_user" name="_user" type="text"/><br />
		<label for="_pass" class="password">##PASSWORD##</label> <input id="_pass" name="_pass" type="password"/><br />
{if $pubkey_exp}
		<input type="hidden" id="_crypt" name="_crypt" value="" />
		<input type="submit" value="##CONNEXION##" onclick="cryptPass();" class="button" />
{else}
		<input type="submit" value="##CONNEXION##" class="button" />
{/if}
	</form>
	{if ($allowaccountcreation)}
	<div class="register"><a href="{kurl app='createaccount'}">##REGISTER##</a></div>
	{/if}
</div>
{/if}
