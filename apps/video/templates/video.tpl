<div align="center">
<p>
{t}Video posted by{/t} : {userlink user=$videoauthor.object showpicture=$islogged}
<br />

<object data="{$url}{$videonow}" type="application/x-shockwave-flash" width="200" height="184">
	<param name="movie" value="{$url}{$videonow}" />
</object>

<br />

{$commentaire}

</p>
<a href="{kurl app="video" page=""}">{t}See the previous videos{/t}</a>
<br />
</div>
