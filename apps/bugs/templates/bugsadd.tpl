<div class="bugs">
	<h1>Bugs</h1>
	<h2>Ajouter un bug</h2>
	<div class="newBugsForm">

		<form action="{kurl action="post"}" method="post">
			
			<div class="field title">
				<label for="title">Titre :</label> <input type="text" id="title" name="title"/>
			</div>
			<SELECT name="module">
				{foreach item=module from=$modules}
				<OPTION>{$module.appli_name}
				{/foreach}
			</SELECT>
			<div class="field browser">
				<label for="browser">Navigateur :</label> <input type="text" id="browser" name="browser" />
			</div>
			<div class="field description">
				<label for="description">Description : </label><textarea name="bug" id="description" rows="10" cols="60" /></textarea>
			</div>

			<input type="radio" name="bug_type" value="amelioration" id="impbug" />
			<label for="impbug">
				<span>Amélioration</span>
			</label>
			<br />
			<input type="radio" name="bug_type" value="mineur" id="minbug"/>
			<label for="minbug">
				<span>Bug mineur</span>
			</label>
			<br />
			<input type="radio" name="bug_type" value="normal" id="normbug" checked />
			<label for="normbug">
				<span>Bug normal</span>
			</label>
			<br />
			<input type="radio" name="bug_type" value="grave" id="majbug" />
			<label for="majbug">
				<span>Bug majeur</span>
			</label>
			<br />
			<input type="radio" name="bug_type" value="securite" id="secbug" />
			<label for="secbug">
				<span>Sécurité</span>
			</label>
			<br />
			
			<div class="button">
				<input type="submit" value="Poster le bug" />
			</div>
		</form>
	</div>
</div>
 
