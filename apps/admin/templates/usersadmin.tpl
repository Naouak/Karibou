
<h1>Users</h1>
<form>
Search for username : <input type="text" name="search" />
</form>

<ul>
{foreach item=user from=$userlist}
	<li><a href="{kurl page='edituser' username=$user->getLogin()}">edit {$user->getLogin()}</a></li>
{/foreach}
</ul>

<h1>Add a user</h1>
<form action="{kurl action='adduser' }" method="POST">
	<label for="input_login">Login : </label>
	<input id="input_login" type="text" name="login" /><br />
	<label for="input_pass">Password : </label>
	<input id="input_pass" type="text" name="pass" /><br />
	<label for="input_email">Email : </label>
	<input id="input_email" type="text" name="email" /><br />
<ul>
{include file="usergrouptree.tpl" grouptree=$grouptree}
</ul>
	<input type="submit" value="save" /><br />
</form>