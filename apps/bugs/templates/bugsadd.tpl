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
				<SELECT name="module">
					{foreach item=module from=$modules}
					<OPTION>{$module.name}
					{/foreach}
				</SELECT>
			</div>
			
			<div class="field explanations">
				<label for="explanations">{t}Explanations{/t}</label>
			</div>
			<div class="field description">
				<label for="description">{t}Description{/t} : </label><textarea name="bug" id="bug" rows="10" cols="60" /></textarea>
			</div>

			<div class="field type">
				<label for="type">{t}Importance{/t} :</label>
				<SELECT name="type">
					<OPTION>{t}IMPROVMENT{/t}
					<OPTION>{t}MINOR{/t}
					<OPTION>{t}NORMAL{/t}
					<OPTION>{t}MAJOR{/t}
					<OPTION>{t}SECURITY{/t}
				</SELECT>
			</div>

			<div class="button">
				<input type="submit" value="Poster le bug" />
			</div>
		</form>
	</div>
</div>
 
