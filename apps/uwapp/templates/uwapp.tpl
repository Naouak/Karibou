<h3 class="handle">{$title}</h3>
<div id="{$id}-uwa">##UWAUNCONF##</div>
<script type="text/javascript">
var BW = new UWA.BlogWidget( 
  {literal}{{/literal} container: document.getElementById('{$id}-uwa'),
    moduleUrl: '{$url}' {literal}} );
BW.setConfiguration(
  { 'borderWidth':'0','color':'#aaaaaa', 'displayTitle':false, 'displayFooter':false } );
{/literal}
</script>
