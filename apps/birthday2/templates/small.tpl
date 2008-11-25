{if count($bdayers) > 0}
<h5>{t}Today we're wishing them a happy birthday:{/t}</h5>
{foreach from=$bdayers item=bdayer }
<span>{userlink user=$bdayer.user showpicture=$islogged showfullname=true}, {t count=$bdayer.age 1=$bdayer.age plural="%1 years old"}%1 year old{/t}</span>
{if ($islogged)}
{if (!$noflashmail)}
		<a href="#" class="sendflashmaillink" onclick="FlashmailManager.Instance.newFlashmail('{$bdayer.user->getSurname()|escape:'javascript'}', {$bdayer.user->getId()}); return false;"><span>Flash</span></a>
{/if}
{/if}
		<br />
{/foreach}
{else}
<h5>{t}No birthdays today{/t}</h5>
{/if}
<br />
<span id="truc">machin</span><br />
<a onclick="n = $app(this).getElementById('truc'); alert('Hop :' + n); if (n.innerHTML=='bidule') n.innerHTML = 'machin'; else n.innerHTML = 'bidule'; return false;">Test</a><br />

