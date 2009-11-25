{if $missingPicture}
<div>No picture available !</div>
{/if}
{if $tof}
<div>
    <div class="default2-daytof-imagecontainer">
	<a href="{$linktof}" class="lightbox" title='{$datofcomment|wordwrap:34:" ":true}' target="_blank"><img src="{$tof}" alt=""></a>
	{if $author_id == $currentuser || $isadmin}
	<div class="default2-daytof-modify">
	    <a onclick="$app(this).modify({$id}); return false;">modifier</a>
	</div>
	    {/if}
	    {if $author_id == $currentuser || $isadmin}
	<div class="default2-daytof-delete">
	    <a onclick="$app(this).deleteContent({$id}); return false;">supprimer<br /></a>
	</div>
	{/if}
    </div>
    <div class="default2-daytof-caption">
		{userlink user=$datofauthor.object showpicture=$islogged} : {$datofcomment|wordwrap:34:" ":true}
    </div>
    <div>
		
        <a href="{kurl app='commentaires' id=$idcombox}" class="lightbox lightbox-iframe">{commentbox id=$idcombox} ##Comments##</a>
    </div>

	
	{votesbox id=$idcombox type="miniapp"}
</div>
{/if}
