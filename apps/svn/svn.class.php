<?php
/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@pinaraf.info>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class SvnModel extends Model
{
	// you would have the function build() in all apps you create
	public function build()
	{
		svn_auth_set_parameter(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true);
		svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_USERNAME, "anonymous");
		svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_PASSWORD, "");

		$last = svn_log("https://svn.karibou.org/", SVN_REVISION_HEAD, SVN_REVISION_HEAD);
		$r = $last[0]["rev"];
		$log = svn_log("https://svn.karibou.org/", $r - intval($this->args["count"]) + 1, $r);
		$log = array_reverse($log);
		$this->assign("log", $log);
	}
}

?>
