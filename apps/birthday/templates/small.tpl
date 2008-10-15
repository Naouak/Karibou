<h3 class="handle">##BDAY_TITLE##</h3>
<div class="bday">
{if count($bdayers) > 0}
<h5>##TODAY_BDAY##</h5>
		
{foreach from=$bdayers item=bdayer }
		    
<span>{userlink user=$bdayer.user showpicture=$islogged showfullname=true}, {$bdayer.age} ##YEARS_OLD##  </span>

{if (!$noflashmail)}
		<a href="{kurl app="flashmail" page="writeto" userid=$bdayer.user->getId()}" class="sendflashmaillink"
            onclick="FlashmailManager.Instance.newFlashmail('{$bdayer.user->getSurname()|escape:'javascript'}', {$bdayer.user->getId()}); return false;"><span>Flash</span></a>
{/if}
		<br />
{/foreach}
		
{else}
<h5>##TODAY_NOBDAY##</h5>
{/if}
</div>
