<h1>##SURVEY##</h1>
<div class="helper">##SURVEY_DEFAULT##</div>
<div class="survey">
	{if ($surveys|@count > 0)}
	<ul class="surveys">
		{foreach from=$surveys item=survey}
		<li><span><a href="{kurl page="displayquestions" surveyid=$survey->getInfo("id")}">{$survey->getInfo("name")}</a> : <em>{$survey->getInfo("description")}</em></span></li>
		{/foreach}
	</ul>
	{else}
	##KS_NOSURVEY##
	{/if}
</div>