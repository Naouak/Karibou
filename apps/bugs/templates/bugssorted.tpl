<TABLE BORDER="1">
	<CAPTION>Bug list</CAPTION>
	<TR>
		<TH> {if $sort == "id" && $order =="ASC"}
			<a href="{kurl page="sort" sort="id" ascdescsort="2" numberpage="$numberpage"}">{t}Id{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="id" ascdescsort="1" numberpage="$numberpage"}">{t}Id{/t}</a>
		{/if} </TH>

		<TH>{if $sort == "summary" && $order =="ASC"}
			<a href="{kurl page="sort" sort="summary" ascdescsort="2" numberpage="$numberpage"}">{t}Summary{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="summary" ascdescsort="1" numberpage="$numberpage"}">{t}Summary{/t}</a>
		{/if}</TH>

		<TH>{if $sort == "bug" && $order =="ASC"}
			<a href="{kurl page="sort" sort="bug" ascdescsort="2" numberpage="$numberpage"}">{t}Bug{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="bug" ascdescsort="1" numberpage="$numberpage"}">{t}Bug{/t}</a>
		{/if}</TH>

		<TH>{if $sort == "browser" && $order =="ASC"}
			<a href="{kurl page="sort" sort="browser" ascdescsort="2" numberpage="$numberpage"}">{t}Browser{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="browser" ascdescsort="1" numberpage="$numberpage"}">{t}Browser{/t}</a>
		{/if}</TH>

		<TH>{if $sort == "module_id" && $order =="ASC"}
			<a href="{kurl page="sort" sort="module_id" ascdescsort="2" numberpage="$numberpage"}">{t}Module{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="module_id" ascdescsort="1" numberpage="$numberpage"}">{t}Module{/t}</a>
		{/if}</TH>

		<TH>{if $sort == "doublon_id" && $order =="ASC"}
			<a href="{kurl page="sort" sort="doublon_id" ascdescsort="2" numberpage="$numberpage"}">{t}Doublon{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="doublon_id" ascdescsort="1" numberpage="$numberpage"}">{t}Doublon{/t}</a>
		{/if}</TH>

		<TH>{if $sort == "state" && $order =="ASC"}
			<a href="{kurl page="sort" sort="state" ascdescsort="2" numberpage="$numberpage"}">{t}State{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="state" ascdescsort="1" numberpage="$numberpage"}">{t}State{/t}</a>
		{/if}</TH>

		<TH>{if $sort == "type" && $order =="ASC"}
			<a href="{kurl page="sort" sort="type" ascdescsort="2" numberpage="$numberpage"}">{t}Type{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="type" ascdescsort="1" numberpage="$numberpage"}">{t}Type{/t}</a>
		{/if}</TH>

		<TH>{if $sort == "reporter" && $order =="ASC"}
			<a href="{kurl page="sort" sort="reporter_id" ascdescsort="2" numberpage="$numberpage"}">{t}Reporter{/t}</a>
		{else}
			<a href="{kurl page="sort" sort="reporter_id" ascdescsort="1" numberpage="$numberpage"}">{t}Reporter{/t}</a>
		{/if}</TH>
	</TR>

	{foreach item=bug from=$bugs}
		<TR>
			<TH>{$bug.id}</TH>
			<TH><a href="{kurl page="view" id=$bug.id}">{$bug.summary}</a></TH>
			<TH>{$bug.bug}</TH>
			<TH>{$bug.browser}</TH>
			<TH>{t}{$bug.module}{/t}</TH>
			<TH>{$bug.doublon_id}</TH>
			<TH>{t}{$bug.state}{/t}</TH>
			<TH>{t}{$bug.type}{/t}</TH>
			<TH>{userlink user=$bug.author.object showpicture=true}</TH>
		</TR>
	{/foreach}
</TABLE>

<p>{if $previous == 1} <a href="{kurl page="sort" sort="id" ascdescsort="1" numberpage="$previouspage"}">{t}Previous{/t}</a> {/if}
{if $next == 1} <a href="{kurl page="sort" sort="id" ascdescsort="1" numberpage="$nextpage"}">{t}Next{/t}</a> {/if}</p>


