<h1>##USER_SEARCH##</h1>
<div class="directorysearch">
	<form>
		<label for="search">##SEARCH_TITLE##</label> <input type="text" name="search" id="search" />
	</form>
	<p>
	{if !isset($userlist)}
	{elseif $userlist|@count == 0}
		##SEARCH##: &quot;<strong>{$search}</strong>&quot;
		<br />
		##NO_RESULT##...
	{elseif $userlist|@count > 0}
		##SEARCH_RESULTS## ##FOR## "<strong>{$search}</strong>" ({$userlist|@count} ##RESULT##{if ($userlist|@count)>1}s{/if}) :
		{foreach item=user from=$userlist}
			<div class="thumbnail">
				<a href="{kurl page="" username=$user->getLogin()}"><img src="{$user->getPicturePath()}" /></a>
				<br />
				<div class="thumbtitle">
						<a href="{kurl page="" username=$user->getLogin()}">
					{if $user->getFirstname()}
							{$user->getFirstname()} {$user->getLastname()} {if $user->getSurname()}({$user->getSurname()}){/if}
					{else}
							{$user->getLogin()}
					{/if}
						</a>
					</div>
			</div>
		{/foreach}
	{/if}
	</p>
</div>