<div id="resetbuttonstatscontent">
<h4>##MYSCORE##</h4>
<div>
	{$myscore} ##POINTS##
</div>
<h4>##MYLONGESTTIME##</h4>
<div>
	##TIME##: {$longestresethour}
</div>
<h4>##YOURESETEDIT##</h4>
<div>
{$myresetcount} ##TIMES##
</div>
<h4>##MYSCOREHISTORY##</h4>
<ol>
	{foreach name=outer item=contact from=$scorelist}
		{if $smarty.foreach.outer.index<10}
		<li>
			##IHAVESCORED## {$contact[1]} ##POINTS## ##ONTHETIME## {$contact[2]}
		</li>
		{/if}
	{/foreach}
</ol>

</div>
