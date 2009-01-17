<span class="StatsContent">
	
	<a onclick="new Effect.toggle($app(this).getElementById('statsPreumsList')); return false;" href="#">##CHAMPIONSOFPREUMS##</a>
	<ol style="list-style-type:decimal; display: none;" id="statsPreumsList">
		<h4>
		{foreach item=contact from=$contacts}
		{if $contact.index<10}
		<li>
			{$contact.iteration} / {userlink user=$contact[0] showpicture=$islogged} : {$contact[1]}
		</li>
		{/if}
		{/foreach}
		</h4>
	</ol>
	<br />
	<a onclick="new Effect.toggle($app(this).getElementById('statsFloodList')); return false;" href="#">##CHAMPIONSOFFLOOD##</a>
	<ol style="list-style-type:decimal; display:none;" id="statsFloodList">
	<h4>
		{foreach name=outer item=contact from=$flooderlist}
		{if $contact.index<10}
		<li>
			{$contact.iteration} / {userlink user=$contact[0] showpicture=$islogged} : {$contact[1]}
		</li>
		{/if}
		{/foreach}
	</h4>
	</ol>
	<br />
	<span>
		<a href="{kurl page=""}">##PREUMSBIGLINK##</a>
	</span>
	<br />
	<a onclick = "new Effect.toggle($app(this).getElementById('help')); return false; " href="#" >##HELP##</a>
	<div style = "display : none; " id="help">
		<p>##PREUMSRULESTITLE##</p>
		<ul>
			<li>
				##DERNZRULES##
			</li>
			<li>
				##PREUMSRULES##
			</li>
			<li>
				##DEUZRULES##
			</li>
			<li>
				##TROIZRULES##
			</li>
		</ul>
	</div>
</span>
