<h3 class="handle">{if count($bdayers) > 1}##BDAY_TITLE##{elseif count($bdayers) ==1}##ONEBDAY##{else}##TODAY_NOBDAY##</h3>
<div class="bday">
{if count($bdayers) > 0}
<h5>##TODAY_BDAY##</h5>
		
{foreach from=$bdayers item=bdayer }
		    
<span>{userlink user=$bdayer.user showpicture=$islogged showfullname=true}, {$bdayer.age} ##YEARS_OLD##  </span>

{if ($islogged)}
{if (!$noflashmail)}
		<a href="#" class="sendflashmaillink" onclick="FlashmailManager.Instance.newFlashmail('{$bdayer.user->getSurname()|escape:'javascript'}', {$bdayer.user->getId()}); return false;"><span>Flash</span></a>
{/if}
{/if}
		<br />
{/foreach}
		
{else}
<h5>##TODAY_NOBDAY##</h5>
{/if}
</div>
