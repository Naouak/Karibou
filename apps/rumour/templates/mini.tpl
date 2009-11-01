
{foreach item=rumour from=$rumours}
    <p><em>{t}On m'a dit dans l'oreillette que : {/t}</em></p>
    <p style="text-align: center;"><strong>&#8220;</strong>&nbsp;{$rumour.rumours}&nbsp;<strong>&#8221;</strong>
	 {if $isadmin}<a onclick="$app(this).modify({$rumour.id}); return false;">modifier</a>{/if}
	 {if $isadmin}<a onclick="$app(this).deleteContent({$rumour.id}); return false;">supprimer</a>{/if}
	</p>
    <hr />
{/foreach}
