Ajouter une application :<br />
<ul>
{foreach item=appName from=$apps}
<li><a href="#" onclick="karibou.instanciateApplication('{$appName}', document.getElementById('appContainer')); return false;">{$appName}</a></li><br />
{/foreach}
</ul>
{*
- {$birthday->getAppName()}<br />
- {$birthday->getMainView()}<br />
- {$birthday->getConfigView()}<br />
- {$birthday->getJsView()}<br />
- {$birthday->getName('fr')}<br />
- {$birthday->getDesc('fr')}<br />
*}
<script type="text/javascript" src="{$karibou_base_url}/themes/js/default2.js"></script>

<script language="javascript">
karibou = null;
Event.observe(window, "load", function() {ldelim}
	karibou = new Karibou("{kurl page="listuserapps"}", "{kurl page="appmainview"}", "{kurl page="appjsview"}");
{rdelim});
function $app (obj) {ldelim}
	return karibou.getAppFromNode(obj);
{rdelim}
</script>
<div id="appContainer">
</div>
{*
{if $keychainError}
<div class="home">
<br />
{if $secondAttempt}
<strong>##INVALID_PASSWORD##</strong><br />
{/if}
##KEYCHAIN_PROBLEM_PASSWORD_OUT_OF_SYNC##<br />
##OLD_PASSWORD_NEEDED##<br />
<form action="{kurl page="keychain"}" method="post">
	##OLD_PASSWORD_FIELD## : <input type="password" name="old_password" id="old_password" /><br />
	<input type="submit" />
</form>
</div>
{else}
<div class="home">
	<br class="spacer" />
	<script language="javascript">
{literal}
Event.observe(window, "load", function() {
	new Ajax.Updater("testApp", {/literal}"{kurl app="default" page="miniappeditview" miniapp="bday_0"}"{literal});
});
{/literal}
	</script>
	<div id="testApp">Something</div>
</div>
{/if}
*}
