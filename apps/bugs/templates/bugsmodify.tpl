<div class="bugs">
	<h1>Bugs</h1>
	<h2>Modifier un bug</h2>
	<div class="newBugsForm">

		<form action='{kurl page="modify" id=$bug.id}' method="post">
		
			<div class="search">
				<label for="search">{t}Search{/t} :</label>
				<input type="text" id="search" name="search" />
			</div>
			
			<div class="button">
				<input type="submit" value="{t}Search{/t}" />
			</div>

			
		</form>
	
		<form action="{kurl action="post"}" method="post">
			<input type="hidden" name="id" value="{$bug.id}" />
			<input type="hidden" name="browser" value="{$browser}" />

			<div class="summary">
				<label for="summary">{t}Summary{/t} :</label>
				<input type="text" id="summary" name="summary" value="{$bug.summary}" />
			</div>
				
			<div class="module">
				<label for="module">{t}Module{/t} :
			<select name="module">
				{foreach item=module from=$modules}
					{if $module.id == $bug.module_id}
						<option selected>{$module.name}</option>
					{else}
						<option>{$module.name}</option>
					{/if}
				{/foreach}
			</select>
			</div>

			<div class="description">
				<label for="description">{t}Description{/t} : </label><textarea name="bug" id="description" rows="10" cols="60" />{$bug.bug} </textarea>
			</div>


			<div class="type">
				<label for="type">{t}Importance{/t} :</label>
				<select name="type">
					<option {if $bug.type == "IMPROVEMENT"}selected{/if}>{t}IMPROVEMENT{/t}</option>
					<option {if $bug.type == "MINOR"}selected{/if}>{t}MINOR{/t}</option>
					<option {if $bug.type == "NORMAL"}selected{/if}>{t}NORMAL{/t}</option>
					<option {if $bug.type == "MAJOR"}selected{/if}>{t}MAJOR{/t}</option>
					<option {if $bug.type == "SECURITY"}selected{/if}>{t}SECURITY{/t}</option>
				</select>
			</div>
			{if $isadmin || $dev}
				<div class="state">
					<label for="state">{t}State{/t} :</label>
					<select name="state">
						<option {if $bug.state == "STANDBY"}selected{/if}>{t}STANDBY{/t}</option>
						<option {if $bug.state == "RESOLVED"}selected{/if}>{t}RESOLVED{/t}</option>
						<option {if $bug.state == "NEEDINFO"}selected{/if}>{t}NEEDINFO{/t}</option>
						<option {if $bug.state == "CONSIDERED"}selected{/if}>{t}CONSIDERED{/t}</option>

					</select>
				</div>

				<div class="developer">
					<label for="developer">{t}Developer{/t} :</label>
					<select name="developer[]" multiple >
						{foreach item=dev from="$devlist"}
							<option value="{$dev->getID()}"> {$dev->getSurname()} </option>
						{/foreach}
					</select>
				</div>

				<div class="doublon">
					<label for="doublon">{t}Choose doublon{/t} </label>
					<ul>
						<li><input type="radio" name="doublon" value="0">Aucun</li>
						{foreach item=dbln from=$search}
							<li><input type="radio" name="doublon" value="{$dbln.id}">{$dbln.summary} </li>
						{/foreach}
					</ul>
				</div>

		
			{elseif $currentuser == $bug.reporter_id && !$bug.doublon_id}


				<div class="state">
					<label for="state">{t}State{/t} :</label>
					<select name="state">
						<option {if $bug.state == "STANDBY"}selected{/if}> {t}STANDBY{/t}</option>
						<option {if $bug.state == "RESOLVED"}selected{/if}> {t}RESOLVED{/t}</option>
					</select>
				</div>

				
			{/if}
			<div class="button">
				<input type="submit" value="{t}Report{/t}" />
			</div>
		</form>
	</div>
</div>


