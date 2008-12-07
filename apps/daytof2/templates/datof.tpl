{if $tof}
<div style="text-align: center;">
	<div style="display: table; width: 100%; height: 200px; margin: 0; margin-bottom: 5px; padding: 0; overflow: hidden;">
		<div style="display: table-cell; vertical-align: middle; margin: 0; padding: 0;">
			<a href="{$linktof}" target="_blank"><img src="{$tof}" alt=""></a>
		</div>
	</div>
	
	<div style="margin:0; padding: 0; height: 4em;">
		{userlink user=$datofauthor.object showpicture=$islogged} : {$datofcomment|wordwrap:34:" ":true}
	</div>
</div>
{/if}
