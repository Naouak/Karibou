
{if ($hasJS)}
//alert("Hello world, I'm loading {$appName} !");
{hook name=JS_$appName}
karibou.registerApplicationClass('{$appName}', {$appName}Class);
{else}
//alert("Ho my god, I don't have any JS to load for {$appName}");
karibou.registerApplicationClass('{$appName}', KApp); 
{/if}

karibou.applicationJSLoaded('{$appName}');
