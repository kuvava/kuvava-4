-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `napiste_nam`;
CREATE TABLE `napiste_nam` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `telefon` varchar(30) COLLATE utf8_czech_ci DEFAULT NULL,
  `zavolame_vam` varchar(5) COLLATE utf8_czech_ci DEFAULT 'ne',
  `email` varchar(70) COLLATE utf8_czech_ci NOT NULL,
  `novinky` varchar(5) COLLATE utf8_czech_ci NOT NULL DEFAULT 'ne',
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `kopie` varchar(5) COLLATE utf8_czech_ci NOT NULL DEFAULT 'ne',
  `dv` date NOT NULL,
  `ov` time NOT NULL,
  `ip` varbinary(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `napiste_nam` (`id`, `jmeno`, `telefon`, `zavolame_vam`, `email`, `novinky`, `text`, `kopie`, `dv`, `ov`, `ip`) VALUES
(1,	'',	'777 790 528',	'ano',	'metodapps@centrum.cz',	'ne',	'<h1>Ahojka</h1>\n\nJak se daří?',	'ne',	'2015-02-15',	'23:31:27',	UNHEX('7F000001')),
(2,	'',	'',	'ne',	'ahojka@nic.cz',	'ne',	'Jak se máš?',	'ne',	'2015-02-15',	'23:33:04',	UNHEX('7F000001'));

DROP TABLE IF EXISTS `stranka`;
CREATE TABLE `stranka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presenter` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `view` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `title_tag` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `content` text COLLATE utf8_czech_ci,
  `vizte_tez` text COLLATE utf8_czech_ci,
  `diskuze` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sdilet` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `clanek` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `archiv` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `submenu` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `actmenu` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `stranka` (`id`, `presenter`, `view`, `title_tag`, `description`, `content`, `vizte_tez`, `diskuze`, `sdilet`, `clanek`, `archiv`, `submenu`, `actmenu`) VALUES
(1,	'Homepage',	'default',	'Jana Urbanová a Tomáš Urban Vás vítají',	'Jana a Tomáš Urbanovi se věnují například léčitelství, poradenství, věštectví a umění. Něco dělají zdarma, něco za peníze.',	'<h1 n:block=title>Vítejte na www.kuvava.cz!</h1>\r\n<p>Jsme Jana a Tomáš Urbanovi. Věnujeme se například umění, léčitelství a poradenství. Něco děláme zdarma, něco za peníze...</p>\r\n<h2>Naše kontaktní údaje</h2>\r\n<dl>\r\n<dt>Telefon:</dt>\r\n<dd><strong>00420 / 777 790 528</strong></dd>\r\n<dt>Email:</dt>\r\n<dd><a href=\"mailto:urbanovi@kuvava.cz\">urbanovi@<!-- -->kuvava.cz</a></dd>\r\n<dt>České číslo účtu:</dt>\r\n<dd><strong>107-3720830217 /&nbsp;0100</strong> (u&nbsp;Komerční banky)<br><small>(Pokud uvedete jako variabilní symbol své mobilní telefonní číslo, zašleme Vám smskou potvrzení, že jsme platbu zdárně obdrželi.)</small></dd>\r\n<dt>Slovenské číslo účtu:</dt>\r\n<dd>18561447 / 6500 (u Poštové banky)</dd>\r\n</dl>',	'<p><a href=\"/\">Velmi zajímavý článek k věci</a></p>\r\n<p><a href=\"/\">Související služba s tematickým popiskem</a></p>\r\n<p><a href=\"/\">Další související služba</a></p>',	1,	1,	0,	0,	NULL,	'm1c1');

-- 2015-02-15 23:45:44
