<form action={kurl action="container"} method="POST">
	<div class="photos-addcontainer-name">
		<label for="name">{t}Veuillez entrer le nom du conteneur que vous voulez créer{/t}<input type="text" name="name" id="name" /></label>
	</div>
	<div class="photos-addcontainer-type" >
		{t}Sélectionner le type de conteneur que vous désirez {/t}
		<select name="type" >
			<option value="album"> album
			<option value="carton"> carton
		</select>
	</div>
	<div class="photos-addcontainer-tags" id="Photos_add_tags">
		<label for="tag">{t}Veuillez saisir la liste des tags correspondants à votre album{/t}<input type="text" id="tags" name="tags"/></label>
	</div>

    <input type="hidden" name="parent" value={$parent} />
	<div class="photos-addcontainer-submit">
		<input type="submit" value="ajouter un conteneur" />
	</div>
</form>
