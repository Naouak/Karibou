<h1>##TITLE##</h1>
<h3>##VIEWING_GROUP## {$thegroup.name}</h3>
{if isset($thegroup.description) && $thegroup.description != ""}
<h5>{$thegroup.description}</h5>
{/if}
<div class="directory">
{assign var="lastgroupname" value=""}
{foreach item=user from=$userlist}
	{if $lastgroupname != $user.groupname}
		{assign var="lastgroupname" value=$user.groupname}
		{assign var="lastgroupid" value=$user.groupid}
	<hr style="clear: both;" />
	<h4><a href="{kurl page="groupid" id=$lastgroupid}">
		{$lastgroupname}
	</h4>
	<br />
	{/if}
	<div class="thumbnail">
			<a href="{kurl page="" username=$user.login}"><img src="{$user.picture}" border="0" /></a>
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
</div>