<div align ="center">
{foreach from=$tofarray key=key item=tof}
	<br />
	<a href="pub/daytof/{$tof.photo}.png" target="_blank">
	<img src="pub/daytof/m{$tof.photo}.png" alt="{$tof.comment}" title="{$tof.comment}" />
	</a>
	<br />
	</br>{$tof.comment}<br />##DAYTOF_POSTED_BY## : {userlink user=$tof.user showpicture=$islogged}
	<p />
{/foreach}
</div>