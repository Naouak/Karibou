<!-- ERRORS -->
{if $error}
<p><strong>##ERROR##:</strong> ##WRONGCONTENT##</p>
{/if}

<!-- LIST ANNOUNCES -->
<p>
{foreach item=annonce from=$annonces}
<div id="annoncecss">
<span id="annoncecsstext">##SURNAMEANN_AUTHOR## </span>: {userlink user=$annonce.author showpicture=$islogged }
<br />
{$annonce.text|wordwrap:34:" ":true}
{if isset($annonce.price) && $annonce.price!=0}
<br />
<span id="annoncecssprix">##prix## </span>{$annonce.price|string_format:"%.2f"}€
{/if}
<br />
{if isset($annonce.expirationdate) && $annonce.expirationdate!='0000-00-00'}
<span id="annoncecssdate">##to## </span>{$annonce.expirationdate}
{/if}
<br />
</div>
{/foreach}

</p>