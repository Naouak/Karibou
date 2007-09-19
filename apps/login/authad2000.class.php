<?php
/**
 * @copyright 2007 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

/**
 * Used to check Log-in of a user on an Active Directory (Windows 2000)
 *
 * @package applications
 */

class AuthAD2000 extends Auth
{
        /**
         * @param String $user
         * @param String $pass
         */
        function login($user, $pass)
        {
                $ldapconn = @ldap_connect($GLOBALS['config']['login']['ADserver']);
                // $ldapconn = @ldap_connect("ns5.elv.enic.fr");
                $ldaprdn = substr($user, 0, strpos($user, "@")) . "@" . $GLOBALS['config']['login']['ADsuffix']; // "ELV.ENIC.FR";
                if ($ldapconn) {
                        // binding to ldap server
                        $ldapbind = @ldap_bind($ldapconn, $ldaprdn, stripslashes($pass));

                        // verify binding
                        if ($ldapbind) {
                                @ldap_close($ldapconn);
                                return true;
                        }
                        @ldap_close($ldapconn);
                }
                return false;
        }

}

?>

