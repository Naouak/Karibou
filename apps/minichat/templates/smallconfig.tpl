##NUMBEROFLINES## : <input type="text" name="maxlines" value="{$maxlines}" /><br />
##USERICHTEXT## : 
<input type="hidden" name="userichtext" value="{$userichtext}" />
<input type="checkbox" id="userichtext1" onclick="if (this.form.userichtext.value!=0) this.form.userichtext.value=0; else this.form.userichtext.value=1;" name="_userichtext" {if $userichtext == 1}checked="checked"{/if}/><br />