
SHOW PROCESSLIST;
SHOW FULL PROCESSLIST;


-- create user, db {
CREATE USER '{username}'@'%' IDENTIFIED BY '{password}';
CREATE USER '{username}'@'%' IDENTIFIED WITH mysql_native_password BY '{password}';
CREATE DATABASE {db_name};
GRANT ALL PRIVILEGES ON {db_name}.{table_name} TO '{username}'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, REFERENCES, INDEX, ALTER ON {db_name}.{table_name} TO '{username}'@'%';

DROP DATABASE {db_name};
REVOKE ALL PRIVILEGES ON {db_name}.{table_name} FROM '{username}'@'%';
DROP USER '{username}'@'%';
-- create user, db }


-- SELECT {
SELECT column1, column2 FROM db.table;
SELECT * FROM db.table WHERE id = 2;
SELECT * FROM db.table WHERE id BETWEEN 1 AND 5;
SELECT * FROM db.table ORDER BY id DESC;
SELECT * FROM db.tableA as A LEFT JOIN tableB as B on A.id = B.a_id ORDER BY A.pos, B.reg;
SELECT max(id) as max_id FROM db.table;

SELECT distinct status, code FROM db.table;
SELECT status, count(*) as cnt FROM db.table GROUP BY status;
-- SELECT }


-- INSERT, UPDATE {
INSERT INTO db.table VALUES ('value1', 'value2');
INSERT INTO db.table (column1, column2) VALUES ('value11', 'value12'), ('value21', 'value22');
INSERT INTO db.table SET column1 = 'value1', column2 = 'value2';
UPDATE      db.table SET column1 = 'value1', column2 = 'value2' WHERE id = 3;
-- INSERT, UPDATE }


-- DELETE {
DELETE FROM db.table WHERE id = 4;
-- DELETE }


-- table {
CREATE TABLE db.table1 (
	no			INT			NOT NULL AUTO_INCREMENT,
	reg_date	DATETIME	NOT NULL DEFAULT CURRENT_TIMESTAMP,
	column1		VARCHAR(50)	NOT NULL COMMENT 'some comment',
	column2		INT			NOT NULL,
	PRIMARY KEY(no),
	INDEX reg_date (reg_date) USING BTREE,
	INDEX column1 (column1) USING BTREE
) ENGINE = MyIsam;

CREATE TABLE IF NOT EXISTS db_1.table_1 LIKE db_2.table_2;
RENAME TABLE db.old_table TO db.new_table;
TRUNCATE db.table;  -- init table
-- table }


-- column {
-- VARCHAR
ALTER TABLE db.table ADD column7 VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'B' COMMENT 'A:xx,B:yy' AFTER column3;
ALTER TABLE db.table ADD column7 VARCHAR(1) NOT NULL DEFAULT 'B' COMMENT 'A:xx,B:yy' AFTER column3;
ALTER TABLE db.table MODIFY COLUMN column7 VARCHAR(1);
ALTER TABLE db.table CHANGE column7_a column7_b VARCHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:xx,B:yy'
-- INT
ALTER TABLE db.table ADD column8 INT NOT NULL AFTER column4;
-- TEXT
ALTER TABLE db.table ADD column9 TEXT NOT NULL AFTER column5;

ALTER TABLE db.table DROP column6;
-- column }


-- index {
ALTER TABLE db.table ADD INDEX index_name (column1, column2) USING BTREE;
ALTER TABLE db.table ADD INDEX index_name (column1);
ALTER TABLE db.table DROP INDEX index_name;
-- index }


-- search column all table
SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'db_name' AND COLUMN_NAME = 'column1';


