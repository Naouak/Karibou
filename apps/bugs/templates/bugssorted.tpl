<table>
	<caption>Bug list</caption>
	<tr>
		<th> {if $sort == "id" && $order =="ASC"}
			<a href="{kurl page="sort" sort="id" ascdescsort="2" numberpage="$numberpage"}">{t}Id{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="id" ascdescsort="1" numberpage="$numberpage"}">{t}Id{/t}</a>
		{/if} </th>

		<th>{if $sort == "summary" && $order =="ASC"}
			<a href="{kurl page="sort" sort="summary" ascdescsort="2" numberpage="$numberpage"}">{t}Summary{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="summary" ascdescsort="1" numberpage="$numberpage"}">{t}Summary{/t}</a>
		{/if}</th>

		<th>{if $sort == "bug" && $order =="ASC"}
			<a href="{kurl page="sort" sort="bug" ascdescsort="2" numberpage="$numberpage"}">{t}Bug{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="bug" ascdescsort="1" numberpage="$numberpage"}">{t}Bug{/t}</a>
		{/if}</th>

		<th>{if $sort == "browser" && $order =="ASC"}
			<a href="{kurl page="sort" sort="browser" ascdescsort="2" numberpage="$numberpage"}">{t}Browser{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="browser" ascdescsort="1" numberpage="$numberpage"}">{t}Browser{/t}</a>
		{/if}</th>

		<th>{if $sort == "module_id" && $order =="ASC"}
			<a href="{kurl page="sort" sort="module_id" ascdescsort="2" numberpage="$numberpage"}">{t}Module{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="module_id" ascdescsort="1" numberpage="$numberpage"}">{t}Module{/t}</a>
		{/if}</th>

		<th>{if $sort == "doublon_id" && $order =="ASC"}
			<a href="{kurl page="sort" sort="doublon_id" ascdescsort="2" numberpage="$numberpage"}">{t}Doublon{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="doublon_id" ascdescsort="1" numberpage="$numberpage"}">{t}Doublon{/t}</a>
		{/if}</th>

		<th>{if $sort == "state" && $order =="ASC"}
			<a href="{kurl page="sort" sort="state" ascdescsort="2" numberpage="$numberpage"}">{t}State{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="state" ascdescsort="1" numberpage="$numberpage"}">{t}State{/t}</a>
		{/if}</th>

		<th>{if $sort == "type" && $order =="ASC"}
			<a href="{kurl page="sort" sort="type" ascdescsort="2" numberpage="$numberpage"}">{t}Type{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="type" ascdescsort="1" numberpage="$numberpage"}">{t}Type{/t}</a>
		{/if}</th>

		<th>{if $sort == "reporter" && $order =="ASC"}
			<a href="{kurl page="sort" sort="reporter_id" ascdescsort="2" numberpage="$numberpage"}">{t}Reporter{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="reporter_id" ascdescsort="1" numberpage="$numberpage"}">{t}Reporter{/t}</a>
		{/if}</th>
	</tr>

	{foreach item=bug from=$bugs}
		<tr>
			<th>{$bug.id}</th>
			<th><a href="{kurl page="view" id=$bug.id}">{$bug.summary}</a></th>
			<th>{$bug.bug}</th>
			<th>{$bug.browser}</th>
			<th>{$bug.module}</th>
			<th>{$bug.doublon_id}</th>
			<th>{t}{$bug.state}{/t}</th>
			<th>{t}{$bug.type}{/t}</th>
			<th>{userlink user=$bug.author.object showpicture=true}</th>
		</tr>
	{/foreach}
</table>

<p>{if $previous == 1} <a href="{kurl page="sort" sort="id" ascdescsort="1" numberpage="$previouspage"}">{t}Previous{/t}</a> {/if}
{if $next == 1} <a href="{kurl page="sort" sort="id" ascdescsort="1" numberpage="$nextpage"}">{t}Next{/t}</a> {/if}</p>


