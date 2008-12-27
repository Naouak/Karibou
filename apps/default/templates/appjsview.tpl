
{if ($hasJS)}
{hook name=JS_$appName}
{else}
var {$appName}Class = Class.create(KApp, {ldelim}{rdelim});
{/if}
{if ($submitFields != "")}
{$appName}Class.submitFields = {$submitFields};
{/if}
{if ($configFields != "")}
{$appName}Class.configFields = {$configFields};
{/if}

karibou.registerApplicationClass('{$appName}', {$appName}Class);

