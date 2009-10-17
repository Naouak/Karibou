<div id="scorelist">
{if $selfrank and !$hide}
	<ul>
{if $app != null}
		<li><strong>{t}Application{/t}:</strong> {$app}</li>
{/if}
		<li><strong>{t}Mon score{/t}:</strong> {$selfscore}</li>
		<li><strong>{t}Mon classement{/t}:</strong> {$selfrank}/{$players}</li>
		<li><strong>{t}Mon classement inversé{/t}:</strong> {$selfrankinv}/{$players}</li>
	</ul>
{/if}
{foreach item=app from=$applis}
	{$app}<br />
{/foreach}
	<p>
		<strong>{t}Classement{/t}:</strong>
	</p>
	<ul>
{foreach item="row" from=$scores}
		<li style="margin-left: 4em;">{$row.rank}/ {userlink user=$row.user showpicture=$islogged}: {$row.score}</li>
{/foreach}
	</ul>
</div>
