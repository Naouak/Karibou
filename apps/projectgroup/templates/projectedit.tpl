<h1>##PROJECTGROUP_TITLE##</h1>
<h2>##CREATEPROJECT##</h2>

<form action="{kurl page="projectsave"}" method="post">
{if isset($project)}
	<input type="hidden" name="projectid" value="{$project->getId()}" />
{/if}
	<label for="name">##PROJECTNAME## : </label>
	<input type="text" name="name" id="name" value="{if isset($project)}{$project->getName()}{/if}" />
	<br />
	<label for="description">##PROJECTDESCRIPTION## : </label>
	<textarea type="description">{if isset($project)}{$project->getDescription()}{/if}</textarea>
	<br />
	<input type="submit" value="##SAVEPROJECT##" />
	(<a href="{kurl page=""}">##CANCEL##</a>)
</form>
