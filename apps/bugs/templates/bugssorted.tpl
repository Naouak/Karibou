<div class="bugs-content bugs-sorted-content">
    <h1>{t}Bug List{/t}</h1>
    <ul>
        <li><a href="{kurl}">##Index##</a></li>
    </ul>

	<form action='{kurl page="sort" sort="module_id" ascdescsort="1" numberpage="$numberpage"}' method="post">
		<h2> Filters </h2>
		<div class="Module">
			<label for="Module">{t}Module{/t} :</label>
			<select name="module_id[]" multiple >
				{foreach item=module from="$modules"}
					<option value="{$module.id}"> {$module.id}-{$module.name} </option>
				{/foreach}
			</select>
		</div>

		<div class="State">
			<label for="State">{t}State{/t} :</label>
			<select name="state[]" multiple>
				<option>{t}STANDBY{/t}</option>
				<option>{t}RESOLVED{/t}</option>
				<option>{t}NEEDINFO{/t}</option>
				<option>{t}CONSIDERED{/t}</option>
				<option>{t}DOUBLON{/t}</option>
			</select>
		</div>

		<div class="Type">
			<label for="Type">{t}Type{/t} :</label>
			<select name="type[]" multiple>
				<option>{t}IMPROVEMENT{/t}</option>
				<option>{t}MINOR{/t}</option>
				<option>{t}NORMAL{/t}</option>
				<option>{t}MAJOR{/t}</option>
				<option>{t}SECURITY{/t}</option>
			</select>
		</div>

		<div class="button">
			<input type="submit" value="{t}Apply{/t}" />
		</div>
	</form>
	
    <table>
        <caption>{t}Bug list{/t}</caption>
        <thead>
            <tr>
                <th>{if $sort == "b.module_id" && $order =="ASC"}
                    <a href="{kurl page="sort" sort="module_id" ascdescsort="2" numberpage="$numberpage"}">{t}Module{/t}</a>
		{else}
                    <a href="{kurl page="sort" sort="module_id" ascdescsort="1" numberpage="$numberpage"}">{t}Module{/t}</a>
		{/if}</th>

                <th>{if $sort == "b.summary" && $order =="ASC"}
                    <a href="{kurl page="sort" sort="summary" ascdescsort="2" numberpage="$numberpage"}">{t}Summary{/t}</a>
		{else}
                    <a href="{kurl page="sort" sort="summary" ascdescsort="1" numberpage="$numberpage"}">{t}Summary{/t}</a>
		{/if}</th>

                <th>{if $sort == "b.state" && $order =="ASC"}
                    <a href="{kurl page="sort" sort="state" ascdescsort="2" numberpage="$numberpage"}">{t}State{/t}</a>
		{else}
                    <a href="{kurl page="sort" sort="state" ascdescsort="1" numberpage="$numberpage"}">{t}State{/t}</a>
		{/if}</th>

                <th>{if $sort == "b.type" && $order =="ASC"}
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
                <a href='{kurl page="sort" sort="module_id" ascdescsort="1" numberpage="$previouspage"}'>
                    {t}Previous{/t}
                </a>
            </li>
        {/if}
        {if $next == 1}
            <li>
                <a href='{kurl page="sort" sort="module_id" ascdescsort="1" numberpage="$nextpage"}'>
                   {t}Next{/t}
                </a>
            </li>
        {/if}
    </ul>



</div>
