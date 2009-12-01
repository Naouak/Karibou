<div class="bugs-content bugs-view-content">
    <h1>##Rapport de bug:## {$bug.title}</h1>
    <ul>
        <li><a href="{kurl}">##Index##</a></li>
        <li><a href='{kurl page="sort" sort="id" ascdescsort="1" numberpage="1"}'>##Bug List##</a></li>
    </ul>
    {if $bug.doublon_id !== null}
        <div><a href='{kurl page="view" id=$bug.doublon_id}'>{t}Doublon n°{/t} {$bug.doublon_id}</a></div>
    {else}
        <div>{votesbox id=$idcombox type="bigapp"}</div>
    {/if}
    <div class="modify">
        {if $bug.reporter_id == $currentuser || $isadmin || $dev}
            <a href='{kurl page="modify" id=$bug.id}'>{t}Modify{/t}</a>
        {/if}
    </div>
    <dl>
        <dd>{t}Post by{/t}</dd><dt>{userlink user=$bug_author.object showpicture=true}</dt>
        <dd>{t}State{/t}</dd><dt>{t}{$bug.state}{/t}</dt>
        <dd>{t}Importance{/t}</dd><dt>{t}{$bug.type}{/t}</dt>
		<dd>{t}Summary{/t}</dd><dt>{t}{$bug.summary}{/t}</dt>
        <dd>{t}Browser{/t}</dd><dt>{$bug.browser}</dt>
        <dd>{t}Description{/t}</dd><dt>{$bug.bug}</dt>
        <dd>{t}Module{/t}</dd><dt>{$module.name}</dt>
    </dl>
</div>