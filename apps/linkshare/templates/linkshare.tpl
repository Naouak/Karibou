{foreach item=link from=$links}
<p>
<b>{userlink user=$link.author showpicture=$isLogged}</b><br /> 
<p>
	<em><a href="{$link.link}" > {$link.title} </a> </em>
</p>
{/foreach}
</p>
