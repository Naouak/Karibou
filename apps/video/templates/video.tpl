<div align="center">
<p>
{t}Video posted by{/t} : {userlink user=$videoauthor.object showpicture=$islogged}
<br />

<object id="videoObject" data="{$url}{$videonow}" type="application/x-shockwave-flash" width="200" height="184">
	<param name="movie" value="{$url}{$videonow}" />
</object>

<br />

{$commentaire}
<p>
	{if $videoauthor == $currentuser || $isadmin}<a onclick="$app(this).modify({$idnow}); return false;">modifier</a>{/if}
	{if $videoauthor == $currentuser || $isadmin}<a onclick="$app(this).deleteContent({$idnow}); return false;">supprimer<br /></a>{/if}
    <div>
        <a href="{kurl app='commentaires' id=$idcomboxnow}" class="lightbox lightbox-iframe">{commentbox id=$idcomboxnow} ##Comments##</a>
    </div>
	{votesbox id=$idcomboxnow type="miniapp"}
</p>

</p>
<a href="{kurl app="video" page=""}">{t}See the previous videos{/t}</a>
<br />
</div>
