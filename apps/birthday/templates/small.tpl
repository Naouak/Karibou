<h3 class="handle">##BDAY_TITLE##</h3>
<div class="bday">
{if count($bdayers) > 0}
<h5>##TODAY_BDAY##</h5>
{foreach from=$bdayers item=bdayer}
	<span>{userlink user=$bdayer.user showpicture=$islogged showfullname=true}, {$bdayer.age} ##YEARS_OLD##</span><br />
{/foreach}
{else}
<h5>##TODAY_NOBDAY##</h5>
{/if}
</div>
