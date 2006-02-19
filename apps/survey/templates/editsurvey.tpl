<h1>##SURVEY##</h1>
<h2>{if isset($mySurvey)}{$mySurvey->getInfo("name")}{else}##KS_CREATESURVEY##{/if}</h2>
<div class="survey">
	<form method="post" action="{kurl action="savesurvey"}" class="survey">
		<fieldset>
			<legend>##KS_SURVEYDETAILS##</legend>
			<ul>
				<li>
					<span>
						<label for="ks_surveyname">##KS_SURVEYNAME##</label>
						<input name="ks_surveyname" value="{if isset($mySurvey)}{$mySurvey->getInfo("name")}{/if}" class="field" />
					</span>
				</li>
				<li>
					<span>
						<label for="ks_surveydescription">##KS_SURVEYDESCRIPTION##</label>
						<input name="ks_surveydescription" value="{if isset($mySurvey)}{$mySurvey->getInfo("description")}{/if}" class="field" />
					</span>
				</li>
			</ul>
		</fieldset>
		<fieldset>
			<legend>##KS_QUESTIONS##</legend>
			<ul>
			{if isset($mySurvey)}
				{foreach name="question" from=$mySurvey->getAllQuestions() item=question}
				<li{if $smarty.foreach.question.first} class="first"{/if}>
					<strong>#{$smarty.foreach.question.iteration}</strong>
					<ul>
						<li>
							<span>
								<label for="ks_q{$question->getInfo("id")}_name">##KS_QUESTIONNAME##</label>
								<input type="text" name="ks_q{$question->getInfo("id")}_name" class="field" value="{$question->getInfo("name")}" />
							</span>
						</li>
						<li>
							<span>
								<label for="ks_q{$question->getInfo("id")}_description">##KS_QUESTIONDESCRIPTION##</label>
								<input type="text" name="ks_q{$question->getInfo("id")}_description" class="field"  value="{$question->getInfo("description")}" />
							</span>
						</li>
						<li>
							<span>
								<label for="ks_q{$question->getInfo("id")}_type">##KS_QUESTIONTYPE##</label>
								<select name="ks_q{$question->getInfo("id")}_type" class="field">
									<option value="text" {if $question->getInfo("type") == "text"}SELECTED{/if}>Text</option>
									<option value="numeric" {if $question->getInfo("type") == "numeric"}SELECTED{/if}>Numeric</option>
									<option value="date" {if $question->getInfo("type") == "date"}SELECTED{/if}>Date</option>
								</select>
							</span>
						</li>
						<li>
							<span>
								<label for="ks_q{$question->getInfo("id")}_delete">##KS_QUESTIONDELETE##</label>
								<input type="checkbox" name="ks_q{$question->getInfo("id")}_delete" class="field" value="delete" />
							</span>
						</li>
					</ul>
				</li>
				{/foreach}
			{/if}
				<li>
					<strong>##KS_ADDQUESTION##</strong>
					<ul>
						<li>
							<span>
								<label for="ks_qn1_name">##KS_QUESTIONNAME##</label>
								<input name="ks_qn1_name" class="field" />
							</span>
						</li>
						<li>
							<span>
								<label for="ks_qn1_description">##KS_QUESTIONDESCRIPTION##</label>
								<input name="ks_qn1_description" class="field" />
							</span>
						</li>
						<li>
							<span>
								<label for="ks_qn1_type">##KS_QUESTIONTYPE##</label>
								<input name="ks_qn1_type" class="field" />
							</span>
						</li>
					</ul>
				</li>
			</ul>
		</fieldset>
		{if isset($mySurvey)}
		<input type="hidden" name="surveyid" value="{$mySurvey->getInfo("id")}" />
		{/if}
		<div class="button">
			<input type="submit" />
		</div>
	</form>
</div>