{if !isset($userlist)}
{elseif $userlist|@count == 0}
	##SEARCH##: &quot;<strong>{$search}</strong>&quot;
	<br />
	##NO_RESULT##...
{elseif $userlist|@count > 0}
	##SEARCH_RESULTS## ##FOR## "<strong>{$search}</strong>" ({$userlist|@count} ##RESULT##{if ($userlist|@count)>1}s{/if}) :
	{foreach item=user from=$userlist}
		<div class="thumbnail">
			<div class="image">
				<a href="{kurl page="" username=$user->getLogin()}"  title="{if $user->getFirstName()}{$user->getFirstName()} {/if}{if $user->getLastName()}{$user->getLastName()} {/if}({if $user->getSurname()} {$user->getSurname()} {else}{$user->getLogin()}{/if})"><img src="{$user->getPicturePath()}" /></a>
			</div>
			<div class="title">
					<a href="{kurl page="" username=$user->getLogin()}" title="{if $user->getFirstName()}{$user->getFirstName()} {/if}{if $user->getLastName()}{$user->getLastName()} {/if}({if $user->getSurname()} {$user->getSurname()} {else}{$user->getLogin()}{/if})">
					{if $user->getFirstname()}
						{$user->getFirstname()} {$user->getLastname()}
					{else}
						{$user->getLogin()}
					{/if}
					</a>
			</div>
		</div>
	{/foreach}
{/if}