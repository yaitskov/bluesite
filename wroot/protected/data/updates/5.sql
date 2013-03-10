
CREATE TABLE tbl_model_file
(
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	oriname varchar(120) NOT NULL, -- original name of file
        description text,        
        mime varchar(20) not null,  -- mime type of file for example text/gcode or text/stl
	status   char(2) NOT NULL,  -- ne for new, de for deleted,
                                    -- ok validated by moderator,
                                    -- ch - changed not validated
                                    -- bn - banned by moderator
	created INTEGER,
        cursize integer,    -- in bytes current size
        -- who loaded the file
	author_id integer NOT NULL references tbl_profile(id) ON DELETE CASCADE,
      	project_id integer NOT NULL references tbl_project(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
