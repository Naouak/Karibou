<div class="bugs-content bugs-module-content">
	<h1>{t}Module List{/t}</h1>
	<ul>
		<li><a href="{kurl}">{t}Index{/t}</a></li>
		<li><p><a href="{kurl page="addmodule"}">{t}Add module{/t}</p></li>
	</ul>
	<table>
		<caption>{t}Module List{/t}</caption>
		<thead>
			<tr>
				<th>{t}Id{/t}</th>
				<th>{t}Nom{/t}</th>
			</tr>
		</thead>
		<tbody>
			{foreach item=module from=$modules}
				<tr>
					<td>{$module.id}</td>
					<td><a href='{kurl page="viewmodule" id=$module.id}'>{$module.name}</a></td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
