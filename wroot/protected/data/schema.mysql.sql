CREATE TABLE tbl_meta
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
        -- holds last update file
        schema_version integer NOT NULL,
        schema_consistent int
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- user = profile 
CREATE TABLE tbl_profile
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
        pro_type CHAR(3) NOT NULL,        
	username VARCHAR(128) NOT NULL unique,
	password VARCHAR(128) NOT NULL,
	email VARCHAR(128) NOT NULL unique,	
	description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tbl_project
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	prname VARCHAR(128) NOT NULL,
	owner_id integer NOT NULL references tbl_profile(id) ON DELETE CASCADE,
	description TEXT,
	status char(2) NOT NULL,
	created integer
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tbl_lookup
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(128) NOT NULL,
	code INTEGER NOT NULL,
	type VARCHAR(128) NOT NULL,
	position INTEGER NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE tbl_post
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(128) NOT NULL,
	content TEXT NOT NULL,
	tags TEXT,
	status INTEGER NOT NULL,
	create_time INTEGER,
	update_time INTEGER,
	author_id INTEGER NOT NULL,
	CONSTRAINT FK_post_author FOREIGN KEY (author_id)
		REFERENCES tbl_profile (id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tbl_tag
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(128) NOT NULL,
	frequency INTEGER DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into tbl_meta (schema_version, schema_consistent) values (1, 1);
INSERT INTO tbl_lookup (name, type, code, position) VALUES ('Draft', 'PostStatus', 1, 1);
INSERT INTO tbl_lookup (name, type, code, position) VALUES ('Published', 'PostStatus', 2, 2);
INSERT INTO tbl_lookup (name, type, code, position) VALUES ('Archived', 'PostStatus', 3, 3);
INSERT INTO tbl_lookup (name, type, code, position) VALUES ('Pending Approval', 'CommentStatus', 1, 1);
INSERT INTO tbl_lookup (name, type, code, position) VALUES ('Approved', 'CommentStatus', 2, 2);

INSERT INTO tbl_profile (username, password, email) VALUES ('demo','$2a$10$JTJf6/XqC94rrOtzuF397OHa4mbmZrVTBOQCmYD9U.obZRUut4BoC','webmaster@example.com');
INSERT INTO tbl_post (title, content, status, create_time, update_time, author_id, tags) VALUES ('Welcome!','This blog system is developed using Yii. It is meant to demonstrate how to use Yii to build a complete real-world application. Complete source code may be found in the Yii releases.

Feel free to try this system by writing new posts and posting comments.',2,1230952187,1230952187,1,'yii, blog');
INSERT INTO tbl_post (title, content, status, create_time, update_time, author_id, tags) VALUES ('A Test Post', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 2,1230952187,1230952187,1,'test');

INSERT INTO tbl_tag (name) VALUES ('yii');
INSERT INTO tbl_tag (name) VALUES ('blog');
INSERT INTO tbl_tag (name) VALUES ('test');