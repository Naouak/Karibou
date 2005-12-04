<h1>Activation des Comptes des Anciens</h1>
{if $item}
<p>
	<strong>Nom : </strong>{$item.nom}<br />
	<strong>Prénom : </strong>{$item.prenom}<br />
	<strong>Promo : </strong>{$item.promo} {$item.promotype}<br />
</p>
<p>
	ces informations sont-elles correctes ?
	<a href="{kurl page='create' key=$key}">oui</a>
	<a href="{kurl page='edit' key=$key}">non</a>
</p>
{else}
<form action="{kurl}">
<fieldset class="largefieldset">
	<legend>Activer un compte</legend>
	<label for="active_key">Saisir une clé d'activation : </label>
	<input id="active_key" type="text" name="key" />
	<br />
	<br />
	<input class="button" type="submit" value="Envoyer" />
</fieldset>
</form>
{/if}