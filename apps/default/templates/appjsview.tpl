{foreach from=$cssfiles item=cssfile}
karibou.loadCSS("{$base_url}{$cssfile}", document);
{/foreach}
{foreach from=$apps item=app}
{assign var='appName' value=$app.appName}
{if ($app.hasJS)}
{hook name=JS_$appName}
{else}
var {$appName}Class = Class.create(KApp, {ldelim}{rdelim});
{if ($app.autorefresh > 0)}
{$appName}Class.autorefresh = {$app.autorefresh};
{/if}
{/if}
{if ($app.submitFields != "")}
{$appName}Class.submitFields = {$app.submitFields};
{/if}
{if ($app.configFields != "")}
{$appName}Class.configFields = {$app.configFields};
{/if}
karibou.registerApplicationClass('{$app.appName}', {$app.appName}Class);
{/foreach}
