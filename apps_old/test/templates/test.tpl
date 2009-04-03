{t}Je teste ma traduction{/t}
<style>

##TESTING_TRAD##

##TESTING_TRAD_RETURN##

##TESTING_TRAD_IOF##

{foreach key=key item=value from=$time}
.start_{$key} {ldelim}
	top: {$value}px;
{rdelim}
{/foreach}

{foreach key=key item=value from=$duration}
.size_{$key} {ldelim}
	height: {$value}px;
{rdelim}
{/foreach}

</style>
