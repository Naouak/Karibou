<ul>
{foreach item=result from=$results}
	<li>{userlink user=$result.user} : {$result.number}</li>
{foreachelse}
##No result.##
{/foreach}
</ul>
