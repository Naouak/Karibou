<?php
/**
 * KacheControl is a system to handle the browser cache. Its
 * main objective is to produce a maximum of 304 status codes
 * in order to use the client cache while keeping a waranty
 * that the client's data are up to date.
 *
 * @author Rémy Sanchez <remy.sanchez@hyperthese.net>
 * @copyright Copyright © 2009 Rémy Sanchez
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License version 2
 */

class KacheControl {
	private static $instance = null;
	private $url, $cu, $db;

	/**
	 * Constructs the class. The access is private because it is a singleton.
	 */
	private function __construct() {
		$this->url = BaseURL::getRef();
		$this->cu = UserFactory::instance()->getCurrentUser();
		$this->db = Database::instance();
	}

	/**
	 * Use this to get an instance of the class
	 */
	public static function instance() {
		if(self::$instance === null) {
			self::$instance = new KacheControl();
		}
		return self::$instance;
	}

	/**
	 * Reply to the current URL with the correct status code accordingly to the
	 * informations that we have about cache. This functions may exit() Karibou,
	 * if the content doesn't have to be generated.
	 */
	public function replyFor() {
		if($GLOBALS["config"]["kache_disable"]) {
			return;
		}

		if($_SERVER['REQUEST_METHOD'] === "GET" && $this->isCacheable()) {
			// Stupid PHP, stupid
			header("Expires:");
			header("Pragma:");

			// Proper cache control
			header("Cache-Control: must-revalidate, private, max-age=0");

			// 3 cases here
			//  1) the browser has no cache
			//  2) the browser has a cache
			//  3) the browser has a cache but wants a fresh version

			if(isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
				// Cache or fresh ?
				if(isset($_SERVER["HTTP_CACHE_CONTROL"])) $cc = $_SERVER["HTTP_CACHE_CONTROL"];
				else $cc = "";

				$etag = intval($_SERVER["HTTP_IF_NONE_MATCH"]);

				// The client asks for something new with the Cache-Control header
				if(strpos($cc, "no-cache") !== false or strpos($cc, "max-age=0") !== false) {
					list(, $infos) = $this->markAsCached();
					self::replyNormal($infos["id"], $infos["date"]);
				// The client can be told "you've got that in your cache !"
				} else {
					$sth = $this->db->prepare("SELECT UNIX_TIMESTAMP(date) AS date FROM kache WHERE id = :id");
					$sth->bindValue(":id", $etag);
					$sth->execute();

					// Was it in cache ?
					if(($row = $sth->fetch()) !== false) {
						self::reply304($etag, $row['date']);
					} else {
						list(, $infos) = $this->markAsCached();
						self::replyNormal($infos["id"], $infos["date"]);
					}
				}
			} elseif(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
				// If the cache you have is newer than the client's one,
				// you answer with 200 and generate the content, else you
				// can just answer 304
				list($present, $infos) = $this->getCacheInfo();

				if($present) {
					self::replyNormal($infos["id"], $infos["date"]);
				} else {
					self::reply304($infos["id"], $infos["date"]);
				}
			} else {
				list(, $infos) = $this->markAsCached();
				self::replyNormal($infos["id"], $infos["date"]);
			}
		} else if($_SERVER['REQUEST_METHOD'] === "POST") {
			// On the slightest modification, we consider that anything
			// could have changed
			$this->renew("%");
		}
	}

	/**
	 * Reply with a 304 code, along with an ETag and a Last-Modified header.
	 * This functions always calls exit().
	 *
	 * @param string $etag an unique identifier for the resource.
	 * @param int $date the timestamp of last modification of the page
	 */
	private static function reply304($etag, $date) {
		// The client already has the page in cache \o/
		header("HTTP/1.1 304 Not Modified");
		header("Last-Modified: " . date("r", $date));
		header("Etag: " . $etag);

		// No need to go further
		exit();
	}

	/**
	 * Let the execution continue its normal flow, but adds ETag and
	 * Last-Modified headers.
	 *
	 * @param string $etag an unique identifier for the resource.
	 * @param int $date the timestamp of last modification of the page
	 */
	private static function replyNormal($etag, $date) {
		// It should be a status 200, but you don't
		// know at this stage if the page exist and
		// is allowed to the user

		header("Last-Modified: " . date("r", $date));
		header("Etag: " . $etag);
	}

	/**
	 * Checks if the URL can be cached, acccording to the configuration.
	 *
	 * @return bool true if the page can be cached.
	 */
	public function isCacheable() {
		$blacklist = isset($GLOBALS["config"]["kache_exclude"]) ? $GLOBALS["config"]["kache_exclude"] : array();
		return !in_array($this->url->getApp(), $blacklist);
	}

	/**
	 * Purge cache for specific application.
	 *
	 * @param string $app name of the app to purge, may contain SQL jokers '%'
	 * @param string $args arguments of the page to purge, may contain SQL jokers '%'
	 */
	public function renew($app, $args = '%') {
		$sth = $this->db->prepare("DELETE FROM kache WHERE app LIKE :app AND args LIKE :args");
		$sth->bindValue(":app", $app);
		$sth->bindValue(":args", $args);
		$sth->execute();
	}

	/**
	 * Returns informations about the cache of the current page.
	 *
	 * @return array(bool, array(int, int)) returns an array with the first line being a bool to tell if the cache exists or not, and the second is an array to tell infos (id, date) of the cache.
	 */
	private function getCacheInfo() {
		$sth = $this->db->prepare("SELECT id, UNIX_TIMESTAMP(date) AS date FROM kache WHERE user_id = :user AND app = :app AND args = :args");
		$sth->bindValue(":user", $this->cu->getID());
		$sth->bindValue(":app", $this->url->getApp());
		$sth->bindValue(":args", $this->url->getArguments());
		$sth->execute();

		if(($row = $sth->fetch()) !== false) {
			return array(true, $row);
		} else {
			return array(false, null);
		}
	}

	/**
	 * Returns infos about the cache. If not cached, create an entry. If cached, update the entry.
	 *
	 * @return array(bool, array(int, int)) returns an array with the first line being a bool to tell if the cache existed before or not, and the second is an array to tell infos (id, date) of the cache.
	 */
	private function markAsCached() {
		// Should I ? Should I not ?
		$this->db->exec("LOCK TABLES kache WRITE");

		list($present, $infos) = $this->getCacheInfo();

		if($present) {
			// The cache is present, you do an update
			$sth = $this->db->prepare("UPDATE kache SET date = NOW() WHERE id = :id");
			$sth->bindValue(":id", $infos["id"]);
			$sth->execute();

			// Then return the infos
			$ret = array($present, $infos);
		} else {
			// The client had no cache, update !
			$sth = $this->db->prepare("INSERT INTO kache (user_id, app, args) VALUES (:user, :app, :args)");
			$sth->bindValue(":user", $this->cu->getID());
			$sth->bindValue(":app", $this->url->getApp());
			$sth->bindValue(":args", $this->url->getArguments());
			$sth->execute();

			// TODO make this pgsql compatible
			// TODO make this less hacky ?
			$ret = array(false, array("id" => $this->db->lastInsertId(), "date" => (time() + 1)));
		}

		// Unlock go !
		$this->db->exec("UNLOCK TABLES");

		return $ret;
	}
}

