{*
Small idea : add these links using JavaScript, so that applications can remove buttons if they don't want them...
*}
<a onclick="$app(this).refresh(); return false;" title="update" href=""><span class="update"><span class="text">update</span><span></a>
<a onclick="$app(this).shade();   return false;" title="shade"  href=""><span class="shade" ><span class="text">shade</span><span></a>
<a onclick="$app(this).close(); return false;"   title="del"    href=""><span class="del"   ><span class="text">del</span><span></a>
<h3 class="handle">{$appTitle}</h3>
<div id="{$appName}_{php}echo time(); {/php}" class="appBox" >
{hook name=$appName}
</div>
