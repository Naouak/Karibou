<h1>##SURVEY##</h1>
<h2>{$mySurvey->getInfo("name")}</h2>
<div class="survey">
	{if $answerssaved}
		<div class="success">##KS_ANSWERSSAVED##</div>
	{else}
		{if $mySurvey->getInfo("description") != ""}
		<div class="helper">
			{$mySurvey->getInfo("description")}
		</div>
		{/if}
	{/if}
	
	<div class="nbquestions"></div>
	
		<form method="post" action="{kurl action="saveanswers"}" class="questions">
			<fieldset>
				<legend>{$mySurvey->getAllQuestions()|@count} ##KS_QUESTION##{if $mySurvey->getAllQuestions()|@count > 0}##KS_S##{/if}</legend>
				<ul>
				{foreach name="question" from=$mySurvey->getAllQuestions() item=question}
					<li{if $smarty.foreach.question.first} class="first"{/if}>
						<span>
							<label for="ks_q{$question->getInfo("id")}" title="{$question->getInfo("name")}">{$question->getInfo("description")}</label>
							<input name="ks_q{$question->getInfo("id")}" value="{$mySurvey->getAnswerById($question->getInfo("id"))}" class="field" />
						</span>
					</li>
				{/foreach}
				</ul>
			</fieldset>
			<input type="hidden" name="surveyid" value="{$mySurvey->getInfo("id")}" />
			<div class="button">
				<input type="submit" />
			</div>
		</form>
</div>