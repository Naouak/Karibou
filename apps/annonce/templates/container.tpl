<h3 class="handle">##SMALLADS##</h3>

<script type="text/javascript">
{literal}
function announceFormSubmit(theform) {
	new Effect.toggle($('{/literal}{$id}{literal}_announce_add'));
	
	$(theform).request({
		onComplete: function(transport){
			$(theform).reset();
			$('{/literal}{$id}{literal}_inner_content').innerHTML = transport.responseText;
		}
	})
}
{/literal}
</script>

<!-- POST A NEW ANNOUNCE -->
<a href="#" onClick="new Effect.toggle($('{$id}_announce_add')); return false;">##ADDANNOUNCE##</a>
<div id="{$id}_announce_add" style="display: none;">
	<form action="{kurl action="add"}" method="post" id="{$id}_add_form">
##textannonce##<br />
		<textarea name="newannonce" cols="30" rows="8"></textarea><br />
##price##<br />
		<input type="number" name="price" /> <br />
##expirationdate## <br />
		<input type="date" name="expirationday" /><br />
		<input type="button" value="##ADDSUBMIT##" onClick="announceFormSubmit('{$id}_add_form');"/>
		<input type="button" value="##ADDCANCEL##" onClick="new Effect.toggle($('{$id}_announce_add')); return false;" />
	</form>
	<br />
	<hr />
	<br />
</div>

<!-- LIST ANNOUNCES -->
<div id="{$id}_inner_content">
	{include file="annonce.tpl" islogged=$islogged annonces=$annonces}
</div>
