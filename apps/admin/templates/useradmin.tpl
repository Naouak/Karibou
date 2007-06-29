<h1>Admin user {$user->getLogin()}</h1>
<form action="{kurl action='modifyuser' }" method="POST">
	<input id="input_id" type="hidden" name="id" value="{$user->getId()}" /><br />
	<label for="input_login">Login : </label>
	<input id="input_login" type="text" name="login" value="{$user->getLogin()}" /><br />
	<label for="input_pass">Password : </label>
	<input id="input_pass" type="text" name="pass" value="" /><br />
	<label for="input_email">Email : </label>
	<input id="input_email" type="text" name="email" value="{$user->getEmail()}" /><br />
<ul>
{include file="usergrouptree.tpl" grouptree=$grouptree}
</ul>



	<input type="submit" value="save" /><br />
</form>
