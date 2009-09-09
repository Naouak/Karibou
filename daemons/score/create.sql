CREATE TABLE `scores` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `app` varchar(20) collate utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Views

CREATE VIEW
	scores_valid
AS
	SELECT
		`scores`.`id` AS `id`,
		`scores`.`user_id` AS `user_id`,
		`scores`.`score` AS `score`,
		`scores`.`app` AS `app`
	FROM
		`scores`
	WHERE
		`scores`.`date` >= FROM_UNIXTIME(0);

-- Total score

CREATE VIEW
	scores_total_sub1
AS
	SELECT
		user_id,
		SUM(score) AS score
	FROM
		scores_valid
	GROUP BY
		user_id
	ORDER BY
		score DESC;

CREATE VIEW
	scores_total_sub2
AS
	SELECT
		s1.user_id AS user_id,
		s1.score AS score,
		COUNT(s2.user_id) AS rank_inv,
		1 as hack
	FROM
		scores_total_sub1 s1
	INNER JOIN
		scores_total_sub1 s2 ON s2.score <= s1.score
	GROUP BY
		s1.user_id
	ORDER BY
		rank_inv DESC;

CREATE VIEW
	scores_total_sub3
AS
	SELECT
		MAX(rank_inv) AS maxrank,
		1 AS hack
	FROM
		scores_total_sub2;

CREATE VIEW
	scores_total
AS
	SELECT
		s.user_id AS user_id,
		s.score AS score,
		c.maxrank - s.rank_inv + 1 AS rank,
		s.rank_inv AS rank_inv
	FROM
		scores_total_sub2 s
	INNER JOIN
		scores_total_sub3 c on c.hack = s.hack

-- Per app score

CREATE VIEW
	scores_app_sub1
AS
	SELECT
		user_id,
		SUM(score) AS score,
		app
	FROM
		scores_valid
	GROUP BY
		user_id,
		app
	ORDER BY
		app ASC,
		score DESC;

CREATE VIEW
	scores_app_sub2
AS
	SELECT
		s1.user_id AS user_id,
		s1.app AS app,
		s1.score AS score,
		COUNT(s2.user_id) AS rank_inv,
		1 as hack
	FROM
		scores_app_sub1 s1
	RIGHT JOIN
		scores_app_sub1 s2 ON (s2.score <= s1.score AND s2.app = s1.app)
	GROUP BY
		s1.user_id,
		s1.app
	ORDER BY
		app ASC,
		rank_inv DESC;

CREATE VIEW
	scores_app_sub3
AS
	SELECT
		MAX(rank_inv) AS maxrank,
		app,
		1 AS hack
	FROM
		scores_app_sub2
	GROUP BY
		app;

CREATE VIEW
	scores_app
AS
	SELECT
		s.user_id AS user_id,
		s.score AS score,
		c.maxrank - s.rank_inv + 1 AS rank,
		s.rank_inv AS rank_inv,
		s.app AS app
	FROM
		scores_app_sub2 s
	INNER JOIN
		scores_app_sub3 c ON (c.hack = s.hack and c.app = s.app);