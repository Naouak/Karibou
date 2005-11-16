<h1>##PROJECTGROUP_TITLE##</h1>
<h2>##PROJECTGROUP_GROUPLIST##</h2>

<div class="projectgroup">

	<div class="projectname">
		##PROJECT## : {$project}
	</div>

	{if $groups|@count > 0}
	##GROUPS## :
		<ul>
		{foreach from=$groups item=group}
			<li class="group">
				<span class="name">{$group->getName()}</span>
				<a href="{kurl page="addmetothisgroup"} groupid=$group->getId()">##ADDMETOTHISGROUP##</a>
				{foreach from=$groups->getMembers() item=member}
					<li>{$member->getFirstName()} {$member->getLastName()}</li>
				{/foreach}
			</li>
		{/foreach}
		</ul>
	{else}
	##NOGROUP##
	{/if}
</div>
