<div id="scorelist">
{if $selfrank and !$hide}
	<ul>
		<li><strong>{t}Mon score{/t}:</strong> {$selfscore}</li>
		<li><strong>{t}Mon classement{/t}:</strong> {$selfrank}</li>
		<li><strong>{t}Mon classement inversé{/t}:</strong> {$selfrankinv}</li>
	</ul>
{/if}

	<p>
		<strong>{t}Classement{/t}:</strong>
	</p>
	<ul>
{foreach item="row" from=$scores}
		<li style="margin-left: 4em;">{$row.rank}/ {userlink user=$row.user showpicture=$islogged}: {$row.score}</li>
{/foreach}
	</ul>
</div>
