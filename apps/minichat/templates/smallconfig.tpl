##NUMBEROFLINES## : <input type="text" name="maxlines" value="{$maxlines}" /><br />
##USERICHTEXT## : 
<input type="hidden" name="userichtext" value="{$userichtext}" />
<input type="checkbox" id="userichtext1" onclick="if (this.form.userichtext.value!=0) this.form.userichtext.value=0; else this.form.userichtext.value=1;" name="_userichtext" {if $userichtext == 1}checked="checked"{/if}/><br />
##INVERSEPOSTORDER## : 
<input type="hidden" name="inversepostorder" value="{$inversepostorder}" />
<input type="checkbox" id="inversepostorder1" onclick="if (this.form.inversepostorder.value!=0) this.form.inversepostorder.value=0; else this.form.inversepostorder.value=1;" name="_inversepostorder" {if $inversepostorder == 1}checked="checked"{/if}/><br />
##EMOTICONTHEME## :
<select name="emoticon_theme">
{foreach from=$emoticon_themes key=key item=theme}
	<br /><option value="{$key}" {if $key == $emoticon_theme}selected{/if}>{$theme}</option>
{/foreach}
</select>