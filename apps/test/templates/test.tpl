
<style>


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
