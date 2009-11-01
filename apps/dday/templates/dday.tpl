{if !isset($DDempty)}
	<table>
	{foreach from=$ddaylist key=key item=dd}
				{if $dd.desc}<tr onmouseover="showhint('{$dd.desc|escape:'javascript'}', 'hint_profile', event)" onmouseout="hidehint()" class="userlink">
				{/if}
				<td>{if $dd.link}<a target="_blank" href="{$dd.link}">{$dd.event}</a>{else}{$dd.event}{/if}<br /><font size="1">{$dd.date}</font></td>
				<td width="15%" valign="top"><b>
{if $dd.JJ>0} {t}D{/t}-{$dd.JJ}
{else} <font color="red">{t}D{/t}</font>
{/if}
				</b></td>
				<td>
				{if $dd.user_id == $currentuser || $isadmin}
				<br /><a onclick="$app(this).modify({$dd.id}); return false;"> modifier </a>
				{/if}
				</td>
				</tr>
	{/foreach}
	</table>
{else}
	Nothing...
{/if}

