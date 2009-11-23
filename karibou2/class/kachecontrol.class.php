<?php
class KacheControl {
	private static $instance = null;

	private function __construct() {
	}

	public static function instance() {
		if(self::$instance === null) {
			self::$instance = new KacheControl();
		}
		return self::$instance;
	}

	public function replyFor() {
		$url = BaseURL::getRef();
		// We only act when method is GET
		if($_SERVER['REQUEST_METHOD'] === "GET" && $_SERVER['SERVER_PROTOCOL'] === strtoupper("HTTP/1.1") && $this->isCacheable($url)) {
			// Remove annoying headers
			header("Pragma:");
			header("Expires:");

			$db = Database::instance();

			// 3 cases here
			//  1) the browser has no cache
			//  2) the browser has a cache
			//  3) the browser has a cache but wants a fresh version

			if(isset($_SERVER["HTTP_IF_NONE_MATCH"]) && !(isset($_SERVER["HTTP_CACHE_CONTROL"]) && strpos($_SERVER['HTTP_CACHE_CONTROL'], "no-cache") === false)) {
				// Case 2 : the browser has a cache, and
				// just asks us to validate it
				$sth = $db->prepare("SELECT user_id FROM kache WHERE id = :etag");
				$sth->bindValue(":etag", $_SERVER["HTTP_IF_NONE_MATCH"]);
				$sth->execute();

				// If the cache is valid, the road ends here for
				// the server
				$user_id = $sth->fetchColumn(0);
				if($user_id !== false && $user_id == UserFactory::instance()->getCurrentUser()->getID()) {
					header("HTTP/1.1 304 Not Modified");
					header("Etag: " . intval($_SERVER["HTTP_IF_NONE_MATCH"]));
					header("Cache-Control: must-revalidate, private, max-age=0");
					exit();
				}
			}

			// Case 1 & 3 : the browser had no previous cache, or
			// wants something fresh
			// Refreshing the etag
			if(isset($_SERVER["HTTP_IF_NONE_MATCH"])) {
				$sth = $db->prepare("SELECT id FROM kache WHERE user_id = :user_id AND app = :app AND args = :args");
				$sth->bindValue(":user_id", UserFactory::instance()->getCurrentUser()->getID());
				$sth->bindValue(":app", $url->getApp());
				$sth->bindValue(":args", $url->getArguments());
				$sth->execute();
				$etag = $sth->fetchColumn(0);

				// Okay, the cache was cleared in the meantime
				if($etag === false) {
					$sth = $db->prepare("INSERT IGNORE INTO kache (user_id, app, args) VALUES (:user, :app, :args)");
					$sth->bindValue(":user", UserFactory::instance()->getCurrentUser()->getID());
					$sth->bindValue(":app", $url->getApp());
					$sth->bindValue(":args", $url->getArguments());
					$sth->execute();
					$etag = $db->lastInsertId();
				}
			} else {
				$sth = $db->prepare("INSERT IGNORE INTO kache (user_id, app, args) VALUES (:user, :app, :args)");
				$sth->bindValue(":user", UserFactory::instance()->getCurrentUser()->getID());
				$sth->bindValue(":app", $url->getApp());
				$sth->bindValue(":args", $url->getArguments());
				$sth->execute();
				$etag = $db->lastInsertId();

				if($etag == 0) {
					$sth = $db->prepare("SELECT id FROM kache WHERE user_id = :user_id AND app = :app AND args = :args");
					$sth->bindValue(":user_id", UserFactory::instance()->getCurrentUser()->getID());
					$sth->bindValue(":app", $url->getApp());
					$sth->bindValue(":args", $url->getArguments());
					$sth->execute();
					$etag = $sth->fetchColumn(0);
				}
			}

			// The browser expects an etag
			// TODO port this on PostgreSQL
			header("Etag: " . $etag);
			header("Cache-Control: must-revalidate, private, max-age=0");
		} elseif($_SERVER['REQUEST_METHOD'] === "POST") {
			$this->renew($url->getApp());
		}
	}

	public function isCacheable(BaseURL $url) {
		$blacklist = isset($GLOBALS["config"]["kache_exclude"]) ? $GLOBALS["config"]["kache_exclude"] : array();
		return !in_array($url->getApp(), $blacklist);
	}

	public function renew($app, $args = '%') {
		$sth = Database::instance()->prepare("DELETE FROM kache WHERE app LIKE :app AND args LIKE :args");
		$sth->bindValue(":app", $app);
		$sth->bindValue(":args", $args);
		$sth->execute();
	}
}

