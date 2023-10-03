-- create user, db {
CREATE USER '{username}'@'%' IDENTIFIED BY '{password}';
CREATE DATABASE {db_name};
GRANT ALL PRIVILEGES ON {db_name}.{table_name} TO '{username}'@'%';
DROP DATABASE {db_name};
DROP USER '{username}'@'%';
-- create user, db }


-- SELECT {
SELECT `field1`, `field2` FROM `db`.`table`;
SELECT * FROM `db`.`table` WHERE `id` = 2;
SELECT * FROM `db`.`table` WHERE `id` BETWEEN 1 AND 5;
SELECT * FROM `db`.`table` ORDER BY `id` DESC;
SELECT * FROM `db`.`tableA` as `A` LEFT JOIN `tableB` as `B` on `A`.`id` = `B`.`a_id` ORDER BY `A`.`pos`, `B`.`reg`;
SELECT max(`id`) as `max_id` FROM `db`.`table`;

SELECT distinct `status`, `code` FROM `db`.`table`;
SELECT `status`, count(*) as `cnt` FROM `db`.`table` GROUP BY `status`;
-- SELECT }


-- INSERT, UPDATE {
INSERT INTO `db`.`table` VALUES ('value1', 'value2');

INSERT INTO `db`.`table` (`field1`, `field2`) VALUES ('value1', 'value2');
UPDATE      `db`.`table` (`field1`, `field2`) VALUES ('value1', 'value2') WHERE `id` = 3;

INSERT INTO `db`.`table` SET `field1` = 'value1', `field2` = 'value2';
UPDATE      `db`.`table` SET `field1` = 'value1', `field2` = 'value2' WHERE `id` = 3;
-- INSERT, UPDATE }


-- DELETE {
DELETE FROM `db`.`table` WHERE `id` = 4;
-- DELETE }


RENAME TABLE `old_table` TO `new_table`;

