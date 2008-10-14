<h3>##STATS##</h3>
{include file="navig.tpl"}
<div id="resetbuttonstatscontent">
<h4>##LONGESTTIME##</h4>
<div>
	##TIME##: {$longestresethour}<br />
	##CUTTER##: {userlink user=$longestcutter showpicture=$islogged}
</div>
<h4>##THEYRESETEDIT##</h4>
<ol>
	{foreach name=outer item=contact from=$reseterlist}
		{if $smarty.foreach.outer.index<10}
		<li>
			{$smarty.foreach.outer.iteration} / {userlink user=$contact[0] showpicture=$islogged} ##HASRESETEDIT## {$contact[1]} ##TIMES##
		</li>
		{/if}
	{/foreach}
</ol>
</div>