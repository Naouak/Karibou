{foreach item=rumour from=$rumours}
	<div style="background-color: #eeeeee;">
		<em>{t}On m'a dit dans l'oreillette que : {/t}</em>
		<div style="float: right;">{votesbox id=$rumour.idcombox type="miniapp"}</div>
	</div>

	<p style="text-align: center;">
		<strong>&#8220;</strong>&nbsp;{$rumour.rumours}&nbsp;<strong>&#8221;</strong>
	</p>

	{if $isadmin}
	<p style="text-align: center;">
		<a onclick="$app(this).modify({$rumour.id}); return false;">modifier</a>
		<a onclick="$app(this).deleteContent({$rumour.id}); return false;">supprimer</a>
	</p>
	{/if}
{/foreach}
