	<script type="text/javascript" language="javascript">
	// <![CDATA[

new Ajax.PeriodicalUpdater('onlineusers_live', '{kurl app="onlineusers" page="list"}',  {literal}
                            {evalScripts: true, frequency: 10, 
                            onSuccess: function (transport) {
                                var msg = "##NOBODYONLINE##";
                                var numUsers = transport.responseText.split('class="userlink"').length - 1;
                                if (numUsers > 1)
                                    msg = numUsers + " ##ONLINEUSERS##";
                                else if (numUsers == 1)
                                    msg = "##ONEONLINEUSER##";
                                $("nbronlineusers_live").innerHTML = msg;
                            }});

function setUserState() {
{/literal}
	var url = '{kurl app="onlineusers" page="setuserstate"}';
{literal}
	new Ajax.Request(url, {
		method: 'post',
		parameters: 'userState=' + encodeURIComponent(document.getElementById("userStateSetter").value) + '&userMood=' + document.getElementById("userMoodSetter").value
	});
	{/literal}
	// 1.5 seconds should be enough for the query
	setTimeout("new Ajax.Updater('onlineusers_live', '{kurl app="onlineusers" page="list"}', {literal}{asynchronous:true, evalScripts:true}{/literal});", 1000);
	{literal}
	return false;
}
{/literal}
	// ]]>
	</script>

<h3 class="handle"><span id="nbronlineusers_live">{include file="nbrusersconnected.tpl"}</span></h3>
<div class="onlineusers">
<div id="onlineusers_live">
{include file="list.tpl"}
</div>
{if $islogged}
	<form onsubmit="return setUserState()">
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
</div>
