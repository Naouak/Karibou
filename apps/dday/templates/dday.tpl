<h3 class="handle">##DDAY##</h3>
{if !isset($DDempty)}
	<table>
	{foreach from=$ddaylist key=key item=dd}
				<tr onmouseover="showhint('{if $dd.desc}{$dd.desc}{else}##NODESC##{/if}', 'hint_profile')" onmouseout="hidehint()" class="userlink">
				<td>{if $dd.link}<a href="{$dd.link}">{$dd.event}</a>{else}{$dd.event}{/if}<br /><font size="1">{$dd.date}</font></td>
				<td width="15%" valign="top"><b>
{if $dd.JJ>0} ##DORJ##-{$dd.JJ} 
{else} <font color="red">##DORJ##</font>
{/if}
				</b></td>
				</tr>
	{/foreach}
	</table>
{/if}

