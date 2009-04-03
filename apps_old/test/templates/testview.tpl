

{*
<form method="post" action="{kurl page="post"}">
    {validate id="fullname" message="Full Name Cannot Be Empty"}
    Full Name: <input type="text" name="FullName" value="{$FullName|escape}"><br />
	
    {validate id="phone" message="Phone Number Must be a Number"}
    Phone :<input type="text" name="Phone" value="{$Phone|escape}" empty="yes"><br />
	
    {validate id="expdate" message="Exp Date not valid"}
    Exp Date: <input type="text" name="CCExpDate" size="8" value="{$CCExpDate|escape}"><br />
	
    {validate id="email" message="Email not valid"}
    Email: <input type="text" name="Email" size="30" value="{$Email|escape}"><br />
	
    {validate id="date" message="Date not valid"}
    Date: <input type="text" name="Date" size="10" value="{$Date|escape}"><br />
	
    {validate id="password" message="passwords do not match"}
    password: <input type="password" name="password" size="10" value="{$password|escape}"><br />
    password2: <input type="password" name="password2" size="10" value="{$password2|escape}"><br />

    <input type="submit">
</form>
*}