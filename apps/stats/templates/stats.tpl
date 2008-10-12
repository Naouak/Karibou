<h3 class="handle">##STATSTITLE##</h3>

<span class="StatsContent">
	
	<h4><a onclick="new Effect.toggle(document.getElementById('statsPreumsList')); return false;" >##CHAMPIONSOFPREUMS##</a></h4>
	<ol style="list-style-type:decimal; display: none;" id="statsPreumsList">
		{foreach name=outer item=contact from=$contacts}
		{if $smarty.foreach.outer.index<10}
		<li>
			{$smarty.foreach.outer.iteration} / {userlink user=$contact[0] showpicture=$islogged} : {$contact[1]}
		</li>
		{/if}
		{/foreach}
	</ol>
	
	<h4><a onclick="new Effect.toggle(document.getElementById('statsFloodList')); return false;" >##CHAMPIONSOFFLOOD##</a></h4>
	<ol style="list-style-type:decimal; display:none;" id="statsFloodList">
{foreach name=outer item=contact from=$flooderlist}
{if $smarty.foreach.outer.index<10}
		<li>
{$smarty.foreach.outer.iteration} / {userlink user=$contact[0] showpicture=$islogged} : {$contact[1]}
		</li>
{/if}
{/foreach}
	</ol>
	<span>
		<a href="{kurl page=""}">##PREUMSBIGLINK##</a>
	</span>
</span>
