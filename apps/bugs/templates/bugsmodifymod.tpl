<div class="module">
	<h1>{t}Module{/t}</h1>
	<h2>{t}Modify Module{/t}</h2>

	<div class="newModuleForm">
		<form action="{kurl action="post2"}" method="post">
			<input type="hidden" name="id" value="{$module.id}" />

			<div class="name">
				<label fort="name">{t}Module name{/t}</label>
				<input type="text" id="name" name="name" value="{$module.name}" />
			</div>

			<div class="developer">
				<label for="developer">{t}Developer{/t} :</label>
				<select name="developer[]" multiple >
					{foreach item=dev from="$devlist"}
						<option value="{$dev->getID()}" {if in_array($dev->getID(),$devs)}selected{/if}> {$dev->getSurname()} </option>
					{/foreach}
				</select>
			</div>

			<div class="button">
				<input type="submit" value="{t}Modify{/t}" />
			</div>
		</form>
	</div>
</div>
