<h3 class="handle">##DAYTOF_TITLE##</h3>

{if $tof}

<center>
{html_image file="$tof" href="$linktof"  target="_blank"}
 <br />
{userlink user=$daytofauthor.object showpicture=$islogged} : {$daytofcomment|wordwrap:34:" ":true}  
{/if}
{if $erreur_daytof} 
<br />	{$erreur_daytof}
{/if}
</center>

