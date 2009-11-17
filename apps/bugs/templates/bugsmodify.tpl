<div class="bugs">
	<h1>Bugs</h1>
	<h2>Modifier un bug</h2>
	{if $isadmin || $dev}
		
		<div class="newBugsForm">

			<form action="{kurl action="post"}" method="post">
				<input type="hidden" name="id" value="{$bug.id}" />

				<div class="field summary">
					<label for="summary">{t}Summary{/t} :</label> <input type="text" id="summary" name="summary" value="{$bug.summary}" />
				</div>
				
				<div class="field module">
					<label for="module">{t}Module{/t} :
				<SELECT name="module">
					{foreach item=module from=$modules}
						{if $module.id == $bug.module_id}
							<OPTION selected>{$module.name}
						{else}
							<OPTION>{$module.name}
						{/if}
					{/foreach}
				</SELECT>
				</div>
				
				<div class="field browser">
					<label for="browser">{t}Browser{/t} :</label> <input type="text" id="browser" name="browser" value="{$bug.browser}"/>
				</div>
				<div class="field description">
					<label for="description">{t}Description{/t} : </label><textarea name="bug" id="description" rows="10" cols="60" />{$bug.bug} </textarea>
				</div>

				<div class="field type">
					<label for="type">{t}Importance{/t} :</label>
					<select name="type">
						<option {if $bug.type == "IMPROVMENT"}selected{/if}>{t}IMPROVMENT{/t}</option>
						<option {if $bug.type == "MINOR"}selected{/if}>{t}MINOR{/t}</option>
						<option {if $bug.type == "NORMAL"}selected{/if}>{t}NORMAL{/t}</option>
						<option {if $bug.type == "MAJOR"}selected{/if}>{t}MAJOR{/t}</option>
						<option {if $bug.type == "SECURITY"}selected{/if}>{t}SECURITY{/t}</option>
					</select>
				</div>
				
				<div class="field state">
					<label for="state">{t}State{/t} :</label>
					<select name="state"
						<option {if $bug.state == "STANDBY"}selected{/if}>{t}STANDBY{/t}</option>
						<option {if $bug.state == "RESOLVED"}selected{/if}>{t}RESOLVED{/t}</option>
						<option {if $bug.state == "NEEDINFO"}selected{/if}>{t}NEEDINFO{/t}</option>
						<option {if $bug.state == "CONSIDERED"}selected{/if}>{t}CONSIDERED{/t}</option>
					</select>
				</div>

				<div class="developer">
					<label for="developer">{t}Developer{/t} :</label>
					<select name="developer[]" multiple>
						{foreach item=dev from="$devlist"}
							<option value="{$dev->getId()}"> {$dev->getSurname()} </option>
						{/foreach}
					</select>
				</div>

				<div class="field doublon">
					<label for="doublon">{t}Doublon n°{/t} </label> <input type="text" id="doublon" name="doublon" value="{$bug.doublon_id}" />
				</div>

				<div class="button">
					<input type="submit" value="{t}Report{/t}" />
				</div>
			</form>
		</div>
	{elseif $currentuser == $bug.reporter_id && !$bug.doublon_id}

		<div class="newBugsForm">

			<form action="{kurl action="post"}" method="post">
				<input type="hidden" name="id" value="{$bug.id}" />

				<div class="field summary">
					<label for="summary">{t}Summary{/t} :</label> <input type="text" id="summary" name="summary" value="{$bug.summary}" />
				</div>

				<div class="field module">
					<label for="module">{t}Module{/t} :
				<SELECT name="module">
					{foreach item=module from=$modules}
						{if $module.id == $bug.module_id}
							<OPTION selected>{$module.name}
						{else}
							<OPTION>{$module.name}
						{/if}
					{/foreach}
				</SELECT>
				</div>

				<div class="field browser">
					<label for="browser">{t}Browser{/t} :</label> <input type="text" id="browser" name="browser" value="{$bug.browser}"/>
				</div>
				<div class="field description">
					<label for="description">{t}Description{/t} : </label><textarea name="bug" id="description" rows="10" cols="60" />{$bug.bug} </textarea>
				</div>

				<div class="field type">
					<label for="type">{t}Importance{/t} :</label>
					<SELECT name="type">
						<OPTION {if $bug.type == "IMPROVMENT"}selected{/if}>{t}IMPROVMENT{/t}
						<OPTION {if $bug.type == "MINOR"}selected{/if}>{t}MINOR{/t}
						<OPTION {if $bug.type == "NORMAL"}selected{/if}>{t}NORMAL{/t}
						<OPTION {if $bug.type == "MAJOR"}selected{/if}>{t}MAJOR{/t}
						<OPTION {if $bug.type == "SECURITY"}selected{/if}>{t}SECURITY{/t}
					</SELECT>
				</div>

				<div class="field state">
					<label for="state">{t}State{/t} :</label>
					<SELECT name="state"
						<OPTION {if $bug.state == "STANDBY"}selected{/if}> {t}STANDBY{/t}
						<OPTION {if $bug.state == "RESOLVED"}selected{/if}> {t}RESOLVED{/t}
					</SELECT>
				</div>

				<div class="button">
					<input type="submit" value="{t}Report{/t}" />
				</div>
			</form>
		</div>
	{/if}
</div>


