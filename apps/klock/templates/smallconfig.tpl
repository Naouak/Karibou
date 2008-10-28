##KLOCK_PREFS_WARNING##<br /><br />

{* selection of mode *}
##KLOCK_MODE##:
<select name="mode" onChange="klock_mode=this.options[this.selectedIndex].value;">
	<option value="analog"{if $mode eq 'analog'} selected="selected"{/if}>##KLOCK_MODE_ANALOG##</option>
	<option value="textual"{if $mode eq 'textual'} selected="selected"{/if}>##KLOCK_MODE_TEXTUAL##</option>
	<option value="binary"{if $mode eq 'binary'} selected="selected"{/if}>##KLOCK_MODE_BINARY##</option>
	<option value="digital"{if $mode eq 'digital'} selected="selected"{/if}>##KLOCK_MODE_DIGITAL##</option>
</select>

<br />

{* selection of imprecision *}
##KLOCK_IMPRECISION##: <input name="imprecision" value={$imprecision} />
