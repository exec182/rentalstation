-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Nov 2021 um 12:21
-- Server Version: 5.5.60-0+deb8u1
-- PHP-Version: 5.6.36-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `verleih`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `asset`
--

CREATE TABLE IF NOT EXISTS `asset` (
`idasset` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `idassettype` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `asset`
--

INSERT INTO `asset` (`idasset`, `Name`, `idassettype`) VALUES
(NULL, 'Bildschirm 1', 1),
(NULL, 'Kamera 1', 4),
(NULL, 'Maus 1', 3),
(NULL, 'Tastatur 1', 2),
(NULL, 'Ringlicht 1', 6);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assettype`
--

CREATE TABLE IF NOT EXISTS `assettype` (
`idassettype` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `prefix` varchar(5) NOT NULL,
  `renttimedays` int(11) NOT NULL COMMENT 'Wie viele Tage darf das Asset geliehen werden',
  `renewals_max` int(11) NOT NULL COMMENT 'Wie oft darf ein Asset des Types ausgeliehen werden'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `assettype`
--

INSERT INTO `assettype` (`idassettype`, `Name`, `prefix`, `renttimedays`, `renewals_max`) VALUES
(1, 'Bildschirm', 'D', 90, 3),
(2, 'Tastatur', 'T', 90, 3),
(3, 'Maus', 'M', 90, 3),
(4, 'Kamera', 'K', 90, 3),
(5, 'USB-Dock', 'UD', 90, 3),
(6, 'Ringlicht', 'RL', 90, 3);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `availableassets`
--
CREATE TABLE IF NOT EXISTS `availableassets` (
`idasset` int(11)
,`idassettype` int(11)
,`Assetname` varchar(45)
,`Typename` varchar(45)
,`prefix` varchar(5)
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rent`
--

CREATE TABLE IF NOT EXISTS `rent` (
`idrent` int(11) NOT NULL,
  `idasset` int(11) NOT NULL,
  `idtenant` int(11) NOT NULL,
  `inquirydate` date DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `renewals` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `rent`
--


-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `rentlist`
--
CREATE TABLE IF NOT EXISTS `rentlist` (
`idasset` int(11)
,`Assetname` varchar(45)
,`idassettype` int(11)
,`Typename` varchar(45)
,`prefix` varchar(5)
,`renewals_max` int(11)
,`renttimedays` int(11)
,`idrent` int(11)
,`inquirydate` date
,`start` datetime
,`end` datetime
,`renewals` int(11)
,`idtenant` int(11)
,`Mail` varchar(100)
,`id` varchar(16)
,`rentlimit` datetime
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tenant`
--

CREATE TABLE IF NOT EXISTS `tenant` (
`idtenant` int(11) NOT NULL,
  `Mail` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `tenant`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`iduser` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(200) NOT NULL,
  `mail` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`iduser`, `username`, `password`, `mail`) VALUES
(1, 'admin', '$argon2i$v=19$m=65536,t=4,p=1$eS8wTDUxWUM5QW91TVREbg$af95GqDaOQy7KjDbg8JDzbZf/AnNWF1e71gpBdyf4SQ', 'postmaster@localhost');

-- --------------------------------------------------------

--
-- Struktur des Views `availableassets`
--
DROP TABLE IF EXISTS `availableassets`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `availableassets` AS select `a`.`idasset` AS `idasset`,`at`.`idassettype` AS `idassettype`,`a`.`Name` AS `Assetname`,`at`.`Name` AS `Typename`,`at`.`prefix` AS `prefix` from ((`asset` `a` join `assettype` `at` on((`a`.`idassettype` = `at`.`idassettype`))) left join `rent` `r` on(((`r`.`idasset` = `a`.`idasset`) and isnull(`r`.`end`)))) where isnull(`r`.`idrent`);

-- --------------------------------------------------------

--
-- Struktur des Views `rentlist`
--
DROP TABLE IF EXISTS `rentlist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rentlist` AS select `a`.`idasset` AS `idasset`,`a`.`Name` AS `Assetname`,`at`.`idassettype` AS `idassettype`,`at`.`Name` AS `Typename`,`at`.`prefix` AS `prefix`,`at`.`renewals_max` AS `renewals_max`,`at`.`renttimedays` AS `renttimedays`,`r`.`idrent` AS `idrent`,`r`.`inquirydate` AS `inquirydate`,`r`.`start` AS `start`,`r`.`end` AS `end`,`r`.`renewals` AS `renewals`,`t`.`idtenant` AS `idtenant`,`t`.`Mail` AS `Mail`,concat(`at`.`prefix`,`a`.`idasset`) AS `id`,(`r`.`start` + interval (`at`.`renttimedays` * (`r`.`renewals` + 1)) day) AS `rentlimit` from (((`rent` `r` join `asset` `a` on((`a`.`idasset` = `r`.`idasset`))) join `assettype` `at` on((`at`.`idassettype` = `a`.`idassettype`))) join `tenant` `t` on((`t`.`idtenant` = `r`.`idtenant`)));

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `asset`
--
ALTER TABLE `asset`
 ADD PRIMARY KEY (`idasset`);

--
-- Indizes für die Tabelle `assettype`
--
ALTER TABLE `assettype`
 ADD PRIMARY KEY (`idassettype`);

--
-- Indizes für die Tabelle `rent`
--
ALTER TABLE `rent`
 ADD PRIMARY KEY (`idrent`), ADD UNIQUE KEY `idrent` (`idrent`);

--
-- Indizes für die Tabelle `tenant`
--
ALTER TABLE `tenant`
 ADD PRIMARY KEY (`idtenant`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`iduser`), ADD UNIQUE KEY `iduser` (`iduser`), ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `asset`
--
ALTER TABLE `asset`
MODIFY `idasset` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=124;
--
-- AUTO_INCREMENT für Tabelle `assettype`
--
ALTER TABLE `assettype`
MODIFY `idassettype` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `rent`
--
ALTER TABLE `rent`
MODIFY `idrent` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=175;
--
-- AUTO_INCREMENT für Tabelle `tenant`
--
ALTER TABLE `tenant`
MODIFY `idtenant` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
