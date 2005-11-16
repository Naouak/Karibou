{if ($allowaccountcreation)}
<h1>##ACCOUNT_CREATION_TITLE##</h1>

<form action="{kurl page="createaccountsave"}" method="post">
		<label for="username">##USERNAME##</label>
		<input type="text" id="username" name="username" />
		<br />
		<label for="password1">##PASSWORD##</label>
		<input type="password" id="password1" name="password1" />
		<br />
		<label for="password2">##REPASSWORD##</label>
		<input type="password" id="password2" name="password2" />
		<br />
		<input type="submit" value="##ACCOUNT_CREATION_BUTTON##" />
		(<a href="{kurl app=""}">##ACCOUNT_CREATION_CANCEL##</a>)
</form>
{else}
<h1>##NO_ACCOUNT_CREATION_TITLE##</h1>
<div class="error">##NO_ACCOUNT_CREATION_DESCRIPTION##</div>
{/if}