CREATE VIEW viewsuche AS 
          SELECT        f.wort
                        ,f.frequency_headlines 
                        , f.frequency_expressions 
                        , f.frequency_total 
                        , f.url
                        ,p.url as purl
                        , p.title
                        , p.description 
                        , p.content
             FROM frequency AS f 
                JOIN PageView as p 
                   ON (f.url=p.url) 
                AND (f.wort = '$wort') 
     ORDER BY f.frequency_headlines DESC, 
                        f.frequency_expressions DESC, 
                        f.frequency_total DESC;
                        
                        
                        CREATE DATABASE search_db;
use search_db;


CREATE TABLE `frequency`
(
`id` bigint(255) NOT NULL AUTO_INCREMENT,
 PRIMARY KEY(`id`),
`url` varchar(512),
`wort` varchar(512),
`frequency_total` int,
`frequency_headlines` int,
`frequency_expressions`  int

);

CREATE TABLE `frequency_time`
(
`url` varchar(512),
 PRIMARY KEY(`url`),
`cr_date` datetime,
`last_update` datetime
);

CREATE TABLE `frequency_url`
(
`id` bigint(255) NOT NULL AUTO_INCREMENT,
 PRIMARY KEY(`id`),
`url` varchar(512),
`active` int

);


CREATE TABLE `frequency_url_m_n`
(
`fk_url_page` bigint(255),
`fk_url_anker` bigint(255)
 

);

CREATE TABLE `PageView`
(
`url` varchar(512),
 PRIMARY KEY(`url`),
`title` varchar(512),
`description` varchar(512),
`content` varchar(512)
 

);

CREATE TABLE `Pseudonyme`
(
`id` bigint(255) , 
`url` varchar(512),
`wort` varchar(512),
`relation` varchar(512),
PRIMARY KEY(`id`,`wort`)

);

CREATE TABLE `frequency_satz`
(
`id` bigint(255) NOT NULL AUTO_INCREMENT,
 PRIMARY KEY(`id`),
`url` varchar(512),
`satz` varchar(512),
`frequency_general` int,
`frequency_headlines` int,
`frequency_expressions`  int

);


CREATE TABLE IF NOT EXISTS `Pseudonyme` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `wort` varchar(128) NOT NULL,
  `relation` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wort` (`wort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO Pseudonyme (wort)
SELECT DISTINCT wort FROM frequency;


delimiter $$
CREATE PROCEDURE insertPseudonyme (IN newWord VARCHAR(512), IN synonym INT)
BEGIN
	   SET @synonymRelation = (SELECT relation FROM Pseudonyme WHERE id = synonym);

	INSERT INTO Pseudonyme (wort,relation) 
	VALUES (newWord, CONCAT(synonym,',',@synonymRelation));

	   SET @idWord = (SELECT id FROM Pseudonyme WHERE wort = newWord);
	   SET @relationWord = (SELECT relation FROM Pseudonyme WHERE wort = newWord);

	UPDATE Pseudonyme 
           SET relation= CONCAT(relation,',',@idWOrd)
         WHERE FIND_IN_SET(id, @relationWord);

END$$
delimiter ;


SHOW PROCEDURE STATUS


CALL insertPseudonyme('test10',1);

SELECT wort
FROM  `Pseudonyme` 
WHERE FIND_IN_SET( id, (

SELECT relation
FROM  `Pseudonyme` 
WHERE wort =  'alles'))



 SELECT v.wort, v.url, v.title, v.description FROM view_search v WHERE wort IN (
SELECT p.wort FROM Pseudonyme p JOIN Pseudonyme l ON FIND_IN_SET(p.id, l.relation) AND l.wort = '$wort'
UNION SELECT '$wort')                    