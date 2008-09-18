{if $tof}
<div style="text-align: center;">
	<div style="display: table; width: 200px; height: 200px; padding: 0; #position: relative; overflow: hidden;">
		<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle; margin: 0; padding: 0;">
			<div style="margin: 0; padding: 0; #position: relative; #top: -50%">
				<a href="{$linktof}" target="_blank"><img src="{$tof}" alt=""></a>
			</div>
		</div>
	</div>
	
	{userlink user=$datofauthor.object showpicture=$islogged} : {$datofcomment|wordwrap:34:" ":true}
</div>
{/if}
