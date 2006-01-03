<h1>##ALLCV##</h1>
<div class="netcv">
	<div class="allcv">
{if $allcv|@count>0}
	<ul>
	{foreach from=$allcv item="cv"}
		<li>
			<span class="name">{$cv.firstname} {$cv.lastname}</span>
			<span class="jobtitle">{$cv.jobtitle}</jobtitle>
			<span class="jobtitle">{$cv.last_modification}</jobtitle>
			
					##AGO1##
					{assign var="since" value=$cv.last_modification}
					{if $since > 86400}
						{$since/86400|string_format:"%d"} ##DAY##{if $since/86400|string_format:"%d" >= 2}##S##{/if}
					{elseif $since > 3600}
						{$since/3600|string_format:"%d"} ##HOUR##{if $since/3600|string_format:"%d" >= 2}##S##{/if}
					{elseif $since > 60}
						{$since/60|string_format:"%d"} ##MINUTE##{if $since/60|string_format:"%d" >= 2}##S##{/if}
					{else}
						{$since} ##SECOND##{if $since >= 2}##S##{/if}
					{/if}
					##AGO2##
		</li>
	{/foreach}
	</ul>
{else}
	##NOCVYET##
{/if}
	</div>
</div>