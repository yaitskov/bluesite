
drop table if exists tbl_comment;

CREATE TABLE tbl_comment
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	content TEXT NOT NULL,
        obj_type char(2) not null,  -- pr for project, fl for file
	status   char(2) NOT NULL,  -- ne for new, de for deleted,
                                    -- ok validated by moderator,
                                    -- ch - changed not validated
                                    -- bn - banned by moderator
	created INTEGER,
        obj_id    integer not null, -- commented object project or file
	author_id integer NOT NULL references tbl_profile(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

