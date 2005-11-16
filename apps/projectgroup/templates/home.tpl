<h1>##PROJECTGROUP_TITLE##</h1>
<div class="projectgroup">

{if $permission > _READ_ONLY_}
<div class="newprojectlink">
<a href="{kurl page="projectedit"}">##CREATENEWPROJECT##</a>
</div>
{/if}

{if $projects|@count > 0}
##PROJECTS## :
<ul>
{foreach from=$projects item=project}
<li><a href="{kurl page="project"}">{$project->getName()}</a> ##BY## {$project->getAuthorDisplayName()}</li>
{/foreach}
</ul>
{else}
##NOPROJECT##
{/if}

</div>
