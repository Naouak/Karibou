{include file="appnav.tpl"}
{foreach item=user from=$userlist}
<div class="thumbnail">
	<img src="{$user.picture}" /><br />
	<div class="thumbtitle">
		<a href="{kurl page="" username=$user.login}">
{if $user.firstname}
		{$user.firstname} {$user.lastname}
{else}
		{$user.login}
{/if}
		</a>
	</div>
</div>
{/foreach}
<br />
<br />