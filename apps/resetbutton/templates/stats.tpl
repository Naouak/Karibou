<div id="resetbuttonstatscontent">

<p><strong style="color: red;">/!\ Attention ! /!\</strong> les scores ne sont plus ici ! Vous pouvez utiliser l'application "Tableau de score" pour visualiser des scores généralisés à tous les jeux de Karibou :) .</p>

<h4>##LONGESTTIME##</h4>
<div>
	##TIME##: {$longestresethour}<br />
	##CUTTER##: {userlink user=$longestcutter showpicture=$islogged}
</div>
<h4>##THEYRESETIT##</h4>
<ol>
	{foreach name=outer item=contact from=$reseterlist}
		{if $smarty.foreach.outer.index<10}
		<li>{userlink user=$contact[0] showpicture=$islogged} ##HASRESETIT## {$contact[1]} ##TIMES##</li>
		{/if}
	{/foreach}
</ol>

<h4>##TOTALTIME##</h4>
<ol>
	{foreach name=outer item=contact from=$timecountlist}
		{if $smarty.foreach.outer.index<10}
		<li>{userlink user=$contact[0] showpicture=$islogged} ##GOTCOUNTER## {$contact[1]}</li>
		{/if}
	{/foreach}
</ol>

<!--
<h4>##SCORETIME##</h4>
<ol>
	{foreach name=outer item=contact from=$scorelist}
		{if $smarty.foreach.outer.index<10}
		<li>{userlink user=$contact[0] showpicture=$islogged} ##SCORED## {$contact[1]} ##POINTS##</li>
		{/if}
	{/foreach}
</ol>

<h4>- ##SCORETIME##</h4>
<ol>
	{foreach name=outer item=contact from=$minusscorelist}
		{if $smarty.foreach.outer.index<10}
		<li>{userlink user=$contact[0] showpicture=$islogged} ##SCORED## {$contact[1]} ##POINTS##</li>
		{/if}
	{/foreach}
</ol>
-->
</div>
