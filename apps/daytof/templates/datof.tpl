<div align="center">

{if $tof}
<a href="{$linktof}" target="_blank"><img src="{$tof}" alt=""></a>
<br />
{userlink user=$datofauthor.object showpicture=$islogged} : {$datofcomment|wordwrap:34:" ":true}
{/if}

<br />
<a href="{kurl app="daytof" page="history"}">##DAYTOF_HISTORY##</a>
</div>