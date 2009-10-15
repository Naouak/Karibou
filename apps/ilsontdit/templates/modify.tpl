{if $tomodify.0.reporter==$currentuser || $isadmin}
<form action="{kurl action="modify"}" method="post">
    <label for="group">group</label><input type="text" name="group" value="{$tomodify.0.group}"/> <br />
    <label for="who"> qui ? </label> <input type="text" name="who" value="{$tomodify.0.who}" /> <br />
    <label for="what"> le  ils l'ont dit </label> <input type="text" name="what" value="{$tomodify.0.message}" /> <br />
    <input type="hidden" name="id" value="{$tomodify.0.id}" />
    <input type="submit" value="modifier" />
</form>
{else}
##NOACCESS##
{/if}
