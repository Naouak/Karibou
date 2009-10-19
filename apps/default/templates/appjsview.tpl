
{if ($hasJS)}
{hook name=JS_$appName}
{else}
var {$appName}Class = Class.create(KApp, {ldelim}{rdelim});
{if ($autorefresh > 0)}
{$appName}Class.autorefresh = {$autorefresh};
{/if}
{/if}
{if ($submitFields != "")}
{$appName}Class.submitFields = {$submitFields};
{/if}
{if ($configFields != "")}
{$appName}Class.configFields = {$configFields};
{/if}

karibou.registerApplicationClass('{$appName}', {$appName}Class);

