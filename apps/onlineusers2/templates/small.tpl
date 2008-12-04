<div id="onlineusers_live" style="overflow-y: auto; max-height: 300px;">
{include file="list.tpl"}
</div>
{if $islogged}
	<form onsubmit="return $app(this).setUserState()">
		<input type="text" id="userStateSetter" name="userStateSetter" length="64" value="{$currentUserState}" />
		<select name="userMoodSetter" id="userMoodSetter">
		{foreach key=moodValue item=moodText from=$moodList}
			{if $moodValue == $currentUserMood}
				<option value="{$moodValue}" selected="selected">{$moodText}</option>
			{else}
				<option value="{$moodValue}">{$moodText}</option>
			{/if}
		{/foreach}
		</select>
	<input type="submit" value="##CHANGESTATE##" />
	</form>
{/if}
