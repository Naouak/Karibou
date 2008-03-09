
<div align ="center">
{if !isset($DDempty)}

	{foreach from=$videosarray key=key item=vid}


<object data="{$vid.site}{$vid.video}" type="application/x-shockwave-flash" width="425" height="350">
	<param name="movie" value="{$vid.site}{$vid.video}" />
</object><br />
</br>{$vid.comment}<br />##VIDEO_POSTED_BY## : {userlink user=$vid.user showpicture=$islogged}
<br /><br />

	{/foreach}

{/if}
</div>
