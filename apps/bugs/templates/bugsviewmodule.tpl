<div class="bugs-content bugs-viewmodule-content">
	<h1> {t}Module{/t} {$module.name}</h1>
	 <ul>
        <li><a href="{kurl }">{t}Index{/t}</a></li>
        <li><a href='{kurl page="modules"}'>{t}Modules{/t}</a></li>
    </ul>

	<div class="modify">
		{if $isadmin}
			<a href='{kurl page="modifymodule" id=$module.id}'>{t}Modify{/t}</a>
        {/if}
    </div>

	<h2>{t}Développeurs{/t} :</h2>
	<ul>
		{foreach item=dev from="$devs"}
			<li>{$dev.user->getSurname()}</li>
		{/foreach}
	</ul>
</div>
