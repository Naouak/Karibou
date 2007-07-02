<ul>
{include file="linkusers2grouptree.tpl" grouptree=$grouptree}

</ul>

<br />
<br />
 
{if $okgrouping} 
	<form method="POST">
	Users list separated by ;
	<br />
	<textarea name="usertogroup" rows="4" cols="20"></textarea>
	<br />
	<input type="checkbox" name="create_allow" value="ok" />Allow to create user if doesn't exist<br />
	<input type="submit" value="save" />
	</form>
{/if}

