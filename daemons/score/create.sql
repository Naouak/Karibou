CREATE VIEW
	scores_valid
AS select `scores`.`id` AS `id`,`scores`.`user_id` AS `user_id`,`scores`.`score` AS `score`,`scores`.`app` AS `app` from `scores` where (`scores`.`date` >= from_unixtime(0));

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
		COUNT(s2.user_id) AS rank,
		1 as hack
	FROM
		scores_total_sub1 s1
	RIGHT JOIN
		scores_total_sub1 s2 ON s2.score >= s1.score
	GROUP BY
		s1.user_id
	ORDER BY
		rank ASC;

CREATE VIEW
	scores_total
AS
	SELECT
		s.user_id AS user_id,
		s.score AS score,
		s.rank AS rank,
		c.maxrank - s.rank + 1 AS rank_inv
	FROM
		scores_total_sub2 s
	INNER JOIN
		scores_total_sub3 c on c.hack = s.hack
