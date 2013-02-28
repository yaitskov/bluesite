CREATE TABLE tbl_follow_project
(
	project_id integer NOT NULL references tbl_project(id) ON DELETE CASCADE,
	follower_id integer NOT NULL references tbl_profile(id) ON DELETE CASCADE,
	created integer,
        primary key (project_id, follower_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
