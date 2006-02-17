{if $page == "home"}
	<li><strong>##SURVEY##</strong></li>
{else}
	<li><a href="{kurl app='survey'}">##SURVEY##</a></li>
{/if}

{if ($page == "displayanswers") && (isset($surveyid)) && ($surveyid != "")}
	<li><strong>##KS_DISPLAYANSWERS##</strong></li>
{elseif (isset($surveyid)) && ($surveyid != "")}
	<li><a href="{kurl page='displayanswers' surveyid=$surveyid}">##KS_DISPLAYANSWERS##</a></li>
{/if}