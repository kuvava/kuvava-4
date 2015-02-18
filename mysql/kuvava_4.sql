-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `koment`;
CREATE TABLE `koment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stranka_id` int(11) NOT NULL,
  `prezdivka` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `web` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(70) COLLATE utf8_czech_ci NOT NULL,
  `novinky` varchar(5) COLLATE utf8_czech_ci NOT NULL DEFAULT 'ne',
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `puvodni_zneni` text COLLATE utf8_czech_ci NOT NULL,
  `redakcni_poznamka` text COLLATE utf8_czech_ci,
  `pohlavi` char(1) COLLATE utf8_czech_ci NOT NULL DEFAULT 'h',
  `vek` varchar(15) COLLATE utf8_czech_ci DEFAULT NULL,
  `dv` datetime NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `upraveno` tinyint(4) NOT NULL DEFAULT '0',
  `smazano` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `stranka_id` (`stranka_id`),
  CONSTRAINT `koment_ibfk_1` FOREIGN KEY (`stranka_id`) REFERENCES `stranka` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `koment` (`id`, `stranka_id`, `prezdivka`, `web`, `email`, `novinky`, `text`, `puvodni_zneni`, `redakcni_poznamka`, `pohlavi`, `vek`, `dv`, `ip`, `upraveno`, `smazano`) VALUES
(1,	1,	'Micina',	'nemám',	'neco@neco.cz',	'ne',	'No teda, není ten Váš web nějak příliš strohý pro oko?',	'Ty pičo, není ten Váš web nějak příliš strohý pro oko?',	'Původně sprosté zvolání jsme nahradili zvoláním citovým - avšak jemnějším.',	'f',	'25 až 30',	'2015-02-17 02:18:29',	UNHEX('7F000001'),	1,	0),
(2,	1,	'Tomáš Urban',	'www.kuvava.cz',	'urbanovi@kuvava.cz',	'ano',	'Udělali jsme jej pro začátek maximálně jednoduše. Věříme, že tím vynikne především textový obsah. Do budoucna budeme graficky, funkčně i obsahově obohacovat dle spontánních zkušeností a vzájemné inspirace s čtenáři... :-)',	'Udělali jsme jej pro začátek maximálně jednoduše. Věříme, že tím vynikne především textový obsah. Do budoucna budeme graficky, funkčně i obsahově obohacovat dle spontánních zkušeností a vzájemné inspirace s čtenáři... :-)',	NULL,	'm',	'30 až 35',	'2015-02-17 02:21:08',	UNHEX('7F000001'),	0,	0),
(3,	1,	'hlasatel pravé víry',	'www.prozrete.com',	'pan@xy.cz',	'ano',	'Všichni jste pomocníci ďábla. Přijměte Ježíše do svého srdce a proste za odpuštění. Měli byste se kát... Jinak skončíte v pekle a budete zažívat věčná muka! Tak je psáno v Písmu Svatém...',	'Všichni jste pomocníci ďábla. Přijměte Ježíše do svého srdce a proste za odpuštění. Měli byste se kát... Jinak skončíte v pekle a budete zažívat věčná muka! Tak je psáno v Písmu Svatém...',	'Ctíme osobní víru uživatele, který vložil tento komentář. Přijde nám však, že tento příspěvek se nedrží daného tématu a vlákna a u budoucích čtenářů by spíše rušil porozumění u probíhající diskuze.',	'h',	'80 nebo více',	'2015-02-17 03:39:00',	UNHEX('7F000001'),	0,	1),
(4,	1,	'Jan Omáčka',	'www.kavarnausedmiveverek.eu',	'info@kavarnausedmiveverek.eu',	'ano',	'Mě se naopak ta grafická jednoduchost velmi líbí. Oceňuji, že má někdo stránky bez reklam a soustředí se spíše na obsah a diskuze, než na velikost loga a lesklé okraje... :-)',	'Mě se naopak ta grafická jednoduchost velmi líbí. Oceňuji, že má někdo stránky bez reklam a soustředí se spíše na obsah a diskuze, než na velikost loga a lesklé okraje... :-)',	NULL,	'm',	'-1',	'2015-02-17 03:50:37',	UNHEX('7F000001'),	0,	0),
(5,	1,	'',	'',	'janka_urbanova@centrum.cz',	'ne',	'Odvedli jste dobrou práci',	'Odvedli jste dobrou práci',	NULL,	'h',	'-1',	'2015-02-17 04:05:45',	UNHEX('7F000001'),	0,	0);

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
  `dv` datetime NOT NULL,
  `ip` varbinary(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `stranka`;
CREATE TABLE `stranka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presenter` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `url1` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `url2` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `number1` int(11) NOT NULL DEFAULT '0',
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

INSERT INTO `stranka` (`id`, `presenter`, `url1`, `url2`, `number1`, `title_tag`, `description`, `content`, `vizte_tez`, `diskuze`, `sdilet`, `clanek`, `archiv`, `submenu`, `actmenu`) VALUES
(1,	'Www',	'',	'',	0,	'Jana Urbanová a Tomáš Urban Vás vítají',	'Jana a Tomáš Urbanovi se věnují například léčitelství, poradenství, věštectví nebo umění. Něco dělají zdarma, něco za peníze.',	'<h1 n:block=title>Vítejte na www.kuvava.cz!</h1>\r\n<p>Jsme Jana a Tomáš Urbanovi. Věnujeme se například umění, léčitelství a poradenství. Něco děláme zdarma, něco za peníze...</p>\r\n<h2>Naše kontaktní údaje</h2>\r\n<dl>\r\n<dt>Telefon:</dt>\r\n<dd><strong>00420 / 777 790 528</strong></dd>\r\n<dt>Email:</dt>\r\n<dd><a href=\"mailto:urbanovi@kuvava.cz\">urbanovi@<!-- -->kuvava.cz</a></dd>\r\n<dt>České číslo účtu:</dt>\r\n<dd><strong>107-3720830217 /&nbsp;0100</strong> (u&nbsp;Komerční banky)<br><small>(Pokud uvedete jako variabilní symbol své mobilní telefonní číslo, zašleme Vám smskou potvrzení, že jsme platbu zdárně obdrželi.)</small></dd>\r\n<dt>Slovenské číslo účtu:</dt>\r\n<dd>18561447 / 6500 (u Poštové banky)</dd>\r\n</dl>',	'<p><a href=\"/\">Velmi zajímavý článek k věci</a></p>\r\n<p><a href=\"/\">Související služba s tematickým popiskem</a></p>\r\n<p><a href=\"/\">Další související služba</a></p>',	1,	1,	0,	0,	NULL,	'm1c1'),
(2,	'Diskuze',	'',	'',	0,	'Vzájemné rozhovory komunity kolem www.kuvava.cz',	'Diskuze o lidech, pohodě a léčitelství... I na jiná témata. Účastní se i autoři webu: Jana Urbanová a Tomáš Urban',	'',	NULL,	1,	1,	0,	0,	NULL,	'm2c3');

-- 2015-02-17 04:14:36
