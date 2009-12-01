<div class="bugs">
	<h1>{t}Bugs{/t}</h1>
	<h2>Signaler un bug</h2>
	<div class="newBugsForm">

		<form action="{kurl action="post"}" method="post">

			<input type="hidden" name="browser" value="{$browser}" />
			<div class="field summary">
				<label for="summary">{t}Summary{/t} :</label> <input type="text" id="summary" name="summary"/>
			</div>
			<div class="field module">
				<label for="module">{t}Module{/t} :</label>
				<select name="module">
					{foreach item=module from=$modules}
					<option>{$module.name}</option>
					{/foreach}
				</select>
			</div>
			
			<div class="field description">
				<label for="description">{t}Description{/t} : </label><textarea name="bug" id="bug" rows="10" cols="60" /></textarea>
			</div>

			<div class="field type">
				<label for="type">{t}Importance{/t} :</label>
				<select name="type">
					<option>{t}IMPROVEMENT{/t}</option>
					<option>{t}MINOR{/t}</option>
					<option>{t}NORMAL{/t}</option>
					<option>{t}MAJOR{/t}</option>
					<option>{t}SECURITY{/t}</option>
				</select>
			</div>

			<div class="button">
				<input type="submit" value="Poster le bug" />
			</div>
		</form>
	</div>
</div>
 
