<div id="scorelist">
	<p>
		<strong>{t}Mon score{/t}:</strong> {$selfscore}
	</p>

	<p>
		<strong>{t}Classement{/t}:</strong>
	</p>
	<ol>
{foreach item="row" from=$scores}
		<li style="margin-left: 4em;">{userlink user=$row.user showpicture=$islogged}: {$row.score}</li>
{/foreach}
	</ol>
</div>
