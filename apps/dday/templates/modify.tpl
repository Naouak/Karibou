{if $currentuser==$dday.0.user_id || $isadmin}
    <form action="{kurl action="modify"}" method="post">
        <label for="event"> événement </label> <input type="text" name="event" value="{$dday.0.event}" /> <br />
        <label for="date">date </label> <input type="text" name="date" value="{$dday.0.date}" /> <br />
        <label for="desc"> description </label> <input type="text" name="desc" value="{$dday.0.desc}" /> <br />
        <label for="URL"> URL </label> <input type="text" name="URL" value="{$dday.0.link}" /><br />
        <input type="hidden" name="id" value="{$dday.0.id}" />
        <input type="submit" value="modifier" />
    </form>
{else}
##NOACCESS##
{/if}
