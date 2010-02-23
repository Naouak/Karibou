
<div align ="center">
{if !isset($DDempty)}

	{foreach from=$videosarray key=key item=vid}


<object data="{$vid.site}{$vid.video}" type="application/x-shockwave-flash" width="425" height="350">
	<param name="movie" value="{$vid.site}{$vid.video}" />
</object><br />
</br>{$vid.comment}<br />{t}Video posted by{/t} : {userlink user=$vid.user showpicture=$islogged}
<br />
    <div>
        <a href="{kurl app='commentaires' id=$vid.idcombox}" class="lightbox lightbox-iframe">{commentbox id=$vid.idcombox} ##Comments##</a>
    </div>
	{votesbox id=$vid.idcombox type="bigapp"}
<br /> <br />

	{/foreach}

{/if}
</div>
