<ul class="appNav">
	<li><a href="{kurl page=""}">##DIRECTORY_HOME##</a></li>
	<li><a href="{kurl page="groups"}">##USER_GROUPS##</a></li>
	{if isset($currentuser_login)}
	<li><a href="{kurl username=$currentuser_login}">##USER_LINK##</a></li>
	{/if}
</ul>