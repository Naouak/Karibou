{if $tof}
<center>
<a href="{$linktof}" target="_blank"><img src="{$tof}" alt=""></a>
<br />
{userlink user=$datofauthor.object showpicture=$islogged} : {$datofcomment|wordwrap:34:" ":true}
</center>
{/if}
