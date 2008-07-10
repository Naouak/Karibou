<h3 class="handle">##STATSTITLE##</h3>
<span class="StatsContent">
##CHAMPIONSOFPREUMS##:<br />
<ol style="list-style-type:decimal">

{foreach name=outer item=contact from=$contacts}
{if $smarty.foreach.outer.index<10}
<li>
{$smarty.foreach.outer.iteration} / {userlink user=$contact[0] showpicture=$islogged} : {$contact[1]}
</li>
{/if}
{/foreach}

</ol>
</span>