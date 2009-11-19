<div class="bugs-content bugs-sorted-content">
    <h1>{t}Bug List{/t}</h1>
    <ul>
        <li><a href="{kurl}">##Index##</a></li>
    </ul>
    <table>
        <caption>{t}Bug list{/t}</caption>
        <thead>
            <tr>
                <th>{if $sort == "module_id" && $order =="ASC"}
                    <a href="{kurl page="sort" sort="module_id" ascdescsort="2" numberpage="$numberpage"}">{t}Module{/t}</a>
		{else}
                    <a href="{kurl page="sort" sort="module_id" ascdescsort="1" numberpage="$numberpage"}">{t}Module{/t}</a>
		{/if}</th>

                <th>{if $sort == "summary" && $order =="ASC"}
                    <a href="{kurl page="sort" sort="summary" ascdescsort="2" numberpage="$numberpage"}">{t}Summary{/t}</a>
		{else}
                    <a href="{kurl page="sort" sort="summary" ascdescsort="1" numberpage="$numberpage"}">{t}Summary{/t}</a>
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

            </tr>
        </thead>
        <tbody>
	{foreach item=bug from=$bugs}
            <tr>
                <td>{$bug.module}</td>
                <td><a href='{kurl page="view" id=$bug.id}'>{$bug.summary}</a></td>
                <td>{t}{$bug.state}{/t}</td>
                <td>{t}{$bug.type}{/t}</td>
            </tr>
	{/foreach}
        </tbody>
    </table>

    <ul>
        {if $previous == 1}
            <li>
                <a href='{kurl page="sort" sort="id" ascdescsort="1" numberpage="$previouspage"}'>
                    {t}Previous{/t}
                </a>
            </li>
        {/if}
        {if $next == 1}
            <li>
                <a href='{kurl page="sort" sort="id" ascdescsort="1" numberpage="$nextpage"}'>
                   {t}Next{/t}
                </a>
            </li>
        {/if}
    </ul>



</div>
