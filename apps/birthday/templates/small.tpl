<style>
{literal}
div.bday h5 {
	font-size : 11px;
	margin-left : 0px;
	padding-left : 0px;
	margin-top : 5px;
}

div.bday span {
	display : block;
}
{/literal}
</style>
<h3 class="handle">##BDAY_TITLE##</h3>
<div class="bday">
{if count($bdayers) > 0}
<h5>##TODAY_BDAY##</h5>
{foreach from=$bdayers item=bdayer}
	<span><a href="{kurl app='annuaire' username=$bdayer.login}">{$bdayer.firstname} {$bdayer.lastname}{if $bdayer.nickname != ""} ({$bdayer.nickname}){/if}</a>, {$bdayer.age} ##YEARS_OLD##</span>
{/foreach}
{else}
<h5>##TODAY_NOBDAY##</h5>
{/if}
</div>
