<div class="daytof-history">
    <ul class="daytof-history-element">
{foreach from=$tofarray key=key item=tof}
	<li>
            <div>
                <a href="pub/daytof/{$tof.photo}" class="lightbox" title="{$tof.comment}" target="_blank">
                    <img src="pub/daytof/{$tof.mphoto}" alt="{$tof.comment}" title="{$tof.comment}" />
                </a>
            </div>
            <div>
                {$tof.comment}
            </div>
            <div>
                ##DAYTOF_POSTED_BY## : {userlink user=$tof.user showpicture=$islogged}
            </div>
            <div>
                {votesbox id=$tof.idcombox type="bigapp"}
            </div>
	</li>
{/foreach}
    </ul>
</div>
