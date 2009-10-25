<?php
class GeolocationFactory {
	private $db, $uf;
	private static $instance;

	private $hungry = array();
	private $sate = array();

	private function __construct($db, $uf) {
		$this->db = $db;
		$this->uf = $uf;
	}

	public static function initialize($db, $uf) {
		if(self::$instance === null) {
			self::$instance = new GeolocationFactory($db, $uf);
		}
	}

	public static function getInstance() {
		return self::$instance;
	}

	public function prepareForUser(User $user) {
		foreach($this->hungry as $info) {
			if($info->getUser() == $user) {
				return $info;
			}
		}

		foreach($this->sate as $info) {
			if($info->getUser() == $user) {
				return $info;
			}
		}

		$info = new GeolocationInfo();
		$info->setUser($user);
		$this->hungry[$user->getID()] = $info;
		return $info;
	}

	public function feedAll() {
		if(empty($this->hungry)) {
			return;
		}

		$sth = $this->db->prepare("
			SELECT
				*
			FROM (
				SELECT
					o.user_id AS user_id,
					IF (
						p.value IS NULL OR p.value = 'b:1;',
						COALESCE(l.location, :fallback),
						:fallback
					) AS location
				FROM
					onlineusers o
				LEFT JOIN
					location l ON (
						o.user_ip & l.user_mask = l.user_ip & l.user_mask
						AND COALESCE((o.proxy_ip & l.proxy_mask = l.proxy_ip & l.proxy_mask), l.proxy_ip IS NULL)
					)
				LEFT JOIN
					prefs p ON p.user_id = o.user_id AND p.name = 'localize'
				ORDER BY
					l.proxy_mask DESC,
					l.user_mask DESC
				) AS t
			GROUP BY
				user_id
		");
		$sth->bindValue(":fallback", _("Inconnu"));
		$sth->execute();

		while($row = $sth->fetch()) {
			if(isset($this->hungry[$row['user_id']])) {
				$this->hungry[$row['user_id']]->setLocation($row['location']);
				$this->sate[$row['user_id']] = $this->hungry[$row['user_id']];
				unset($this->hungry[$row['user_id']]);
			}
		}
	}

	public function ipToLocation($ip, $proxy) {
		$sth = $this->db->prepare("
			SELECT
				l.location
			FROM
				location l
			WHERE
				INET_ATON(:ip) & user_mask = user_ip & user_mask
				AND COALESCE((INET_ATON(:proxy) & proxy_mask = proxy_ip & proxy_mask), l.proxy_ip IS NULL)
			ORDER BY
				proxy_mask DESC,
				user_mask DESC
			LIMIT
				1
		");
		$sth->bindValue(":ip", $ip);
		$sth->bindValue(":proxy", $proxy);
		$sth->execute();

		return $sth->fetchColumn(0);
	}
}

