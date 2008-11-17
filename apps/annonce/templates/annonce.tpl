<!-- ERRORS -->
{if $error}
<p><strong>##ERROR##:</strong> ##WRONGCONTENT##</p>
{/if}

<!-- LIST ANNOUNCES -->
<p>
{foreach item=annonce from=$annonces}
##SURNAMEANN_AUTHOR## : {userlink user=$annonce.author showpicture=$islogged }
<br />
{$annonce.text|wordwrap:34:" ":true}
{if isset($annonce.price) && $annonce.price!=0}
##prix## {$annonce.price|string_format:"%.2f"}€
{/if}
{if isset($annonce.expirationdate) && $annonce.expirationdate!='0000-00-00'}
##to## {$annonce.expirationdate}
{/if}
<br />
{/foreach}
</p>