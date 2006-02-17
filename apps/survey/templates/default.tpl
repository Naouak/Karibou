<h1>##SURVEY##</h1>
<div class="helper">##SURVEY_DEFAULT##</div>
<div class="survey">
	{if ($surveys|@count > 0)}
	<ul class="surveys">
		{foreach from=$surveys item=survey}
		<li class="survey">
			<span>
				<strong>{$survey->getInfo("name")}</strong> :
				<em>{$survey->getInfo("description")}</em>
			</span>
			<ul>
				<li><a href="{kurl page="displayquestions" surveyid=$survey->getInfo("id")}">
				{if $survey->userAnswered()}##KS_ANSWERSURVEYAGAIN##{else}##KS_ANSWERSURVEY##{/if}
				</a></li>
				{if ($admin)}<li><a href="{kurl page="displayanswers" surveyid=$survey->getInfo("id")}">##KS_SEEALLANSWERS##</a></li>{/if}
			</ul>
		</li>
		{/foreach}
	</ul>
	{else}
	##KS_NOSURVEY##
	{/if}
</div>