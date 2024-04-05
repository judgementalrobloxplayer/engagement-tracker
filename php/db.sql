CREATE DATABASE IF NOT EXISTS `engagement`;
USE `engagement`;

CREATE TABLE IF NOT EXISTS `attended` (
  `eventID` int NOT NULL,
  `email` varchar(320) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `events` (
  `eventName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `field` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `sport` text COLLATE utf8mb4_general_ci,
  `description` longtext COLLATE utf8mb4_general_ci,
  `points` int NOT NULL DEFAULT '0',
  `eventID` int NOT NULL DEFAULT '-1',
  `numAttended` int DEFAULT '0',
  PRIMARY KEY (`eventID`),
  UNIQUE KEY `eventID` (`eventID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `fields` (
  `fieldName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `latitude` double DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0',
  `fieldID` int NOT NULL,
  PRIMARY KEY (`fieldID`),
  UNIQUE KEY `title` (`fieldName`),
  UNIQUE KEY `fieldID` (`fieldID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `sports` (
  `sportsName` longtext COLLATE utf8mb4_general_ci,
  `points` int DEFAULT '1',
  `sportID` int NOT NULL,
  PRIMARY KEY (`sportID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `user` (
  `email` varchar(320) COLLATE utf8mb4_general_ci NOT NULL,
  `pw` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `points` int DEFAULT '0',
  `creationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `totalPoints` int DEFAULT '0',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
