--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.2.63.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 23.06.2017 10:43:48
-- Версия сервера: 5.6.12-log
-- Версия клиента: 4.1
--


-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE spellink;

--
-- Описание для таблицы dict
--
DROP TABLE IF EXISTS dict;
CREATE TABLE dict (
  word VARCHAR(50) NOT NULL,
  PRIMARY KEY (word)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 102
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы userdict
--
DROP TABLE IF EXISTS userdict;
CREATE TABLE userdict (
  word VARCHAR(50) NOT NULL,
  PRIMARY KEY (word)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 174
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы website
--
DROP TABLE IF EXISTS website;
CREATE TABLE website (
  url VARCHAR(255) DEFAULT NULL,
  allowcheck BIT(1) DEFAULT b'1',
  lastcheck TIMESTAMP NULL DEFAULT NULL,
  id INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id),
  UNIQUE INDEX UK_website_url (url)
)
ENGINE = INNODB
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы webpage
--
DROP TABLE IF EXISTS webpage;
CREATE TABLE webpage (
  pageid INT(11) NOT NULL AUTO_INCREMENT,
  url VARCHAR(4000) NOT NULL,
  site_id INT(11) DEFAULT NULL,
  checktime TIMESTAMP NULL DEFAULT NULL,
  spellresult TEXT DEFAULT NULL,
  spelltime TIMESTAMP NULL DEFAULT NULL,
  ignorespell BIT(1) DEFAULT b'0',
  PRIMARY KEY (pageid),
  CONSTRAINT FK_webpage_site_id FOREIGN KEY (site_id)
    REFERENCES website(id) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 344
AVG_ROW_LENGTH = 278
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы weblink
--
DROP TABLE IF EXISTS weblink;
CREATE TABLE weblink (
  link_id INT(11) NOT NULL AUTO_INCREMENT,
  page_id INT(11) NOT NULL,
  line INT(11) DEFAULT NULL,
  pos INT(11) DEFAULT NULL,
  URL VARCHAR(4000) NOT NULL,
  error VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (link_id),
  CONSTRAINT FK_weblink_page_id FOREIGN KEY (page_id)
    REFERENCES webpage(pageid) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 37
AVG_ROW_LENGTH = 1365
CHARACTER SET utf8
COLLATE utf8_general_ci;

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;