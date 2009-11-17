{if $isLoggedIn}
<div class="login">
	<div class="logout"><a href="{kurl page="logout"}">##LOGOUT##</a></div>
	<div class="editprofile"><a href="{kurl app="annuaire" username=$username act='edit'}">##EDITPROFILE##</a></div>
	<div class="changepassword"><a href="{kurl app="changepassword"}">##CHANGEPASSWORD##</a></div>
</div>
{else}

<div class="login">
{section name=m loop=$loginMessages step=1}
	<div class="error">
		{$loginMessages[m].1}
	</div>
{/section}
	<form id="loginForm" action="{kurl app="login"}" onsubmit="$app(this).cryptLoginData();" method="post">
		<label for="_user" class="username">##USERNAME##</label> <input id="_user" name="_user" type="text"/><br />
		<label for="_pass" class="password">##PASSWORD##</label> <input id="_pass" name="_pass" type="password"/><br />
		<input type="submit" value="##CONNEXION##" class="button" />
	</form>
	{if ($allowaccountcreation)}
	<div class="register"><a href="{kurl app='createaccount'}">##REGISTER##</a></div>
	{/if}
</div>
{/if}
