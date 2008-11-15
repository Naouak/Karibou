{if $group_admin == TRUE }
	<h1>##ADMINISTRATION##</h1>
	<h3>##VIEWING_GROUP## 
	{if isset($thegroup.url) && $thegroup.url != ""}
	<a href="{$thegroup.url}">{$thegroup.name}</a>
	{else}
	{$thegroup.name}
	{/if}
	{if isset($thegroup.ml) && $thegroup.ml != ""}
- 	{$thegroup.ml}
	{/if}
	{if isset($thegroup.description) && $thegroup.description != ""}
	<h5>{$thegroup.description}</h5>
	{/if}
	</h3>
	<div class="directory">
	{assign var="lastgroupname" value=""}
	<form action="{kurl action='modifygroup'}" method="POST" enctype="multipart/form-data">
	{foreach item=user from=$userlist}
		{if $lastgroupname != $user.groupname}
			{assign var="id" value=$u['id']}
			{assign var="lastgroupname" value=$user.groupname}
			{assign var="lastgroupid" value=$user.groupid}
			<hr style="clear: both;" />
			<h4><a href="{kurl page="groupid" id=$lastgroupid}">{$lastgroupname}</a></h4>
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
				{if $user.role == "admin" }
				<br />[Admin]
				{/if}
				</a>
				<select name="option_{$user.id}">
					<option value="nothing">Ne rien faire</option>
					<option value="remove">Virer la personne</option>
					<option value="{if $user.role == 'admin' }member{else}admin{/if}">
						{if $user.role == "admin" }Enlever le controle{else}Passer le controle{/if}
					</option>
				</select>
			</div>
		</div>
	{/foreach}
	<br style="clear: both;" />
	Changer la description du groupe
	<input type="text" name="changedescription" size="80" value="{$thegroup.description}"/>
	<center><input type="submit" value="Valider les changements" /></center></br>
	<hr style="clear: both;" />
	<input type="hidden" name="groupid" value="{$thegroup.id}" />
	<input type="hidden" name="description" value="{$thegroup.description}"/>
	</form>
	<br />
	<br />

	</div>
{else}##NOACCESS##
{/if}

			  

