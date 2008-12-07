
{if ($hasJS)}
//alert("Hello world, I'm loading {$appName} !");
{hook name=JS_$appName}
{else}
//alert("Ho my god, I don't have any JS to load for {$appName}");
{$appName}Class = Class.create(KApp, {ldelim}{rdelim});
{/if}
{if ($submitFields != "")}
{$appName}Class.submitFields = {$submitFields};
{/if}

karibou.registerApplicationClass('{$appName}', {$appName}Class);

karibou.applicationJSLoaded('{$appName}');
