<h3 class="handle">##STATSTITLE##</h3>
<span class="StatsContent">
<h4>##CHAMPIONSOFPREUMS##</h4>
<ol style="list-style-type:decimal">

{foreach name=outer item=contact from=$contacts}
<li>
{$smarty.foreach.outer.iteration} / {userlink user=$contact[0] showpicture=$islogged} : {$contact[1]}
</li>
{/foreach}

</ol>
</span>
<h4>##CHAMPIONSOFFLOOD##</h4>
<span>
<ol style="list-style-type:decimal">

{foreach name=outer item=contact from=$flooderlist}
<li>
{$smarty.foreach.outer.iteration} / {userlink user=$contact[0] showpicture=$islogged} : {$contact[1]}
</li>
{/foreach}

</ol>
	
</span>