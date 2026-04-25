-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 10:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cwp_roster`
--

-- --------------------------------------------------------

--
-- Table structure for table `cw_county`
--

CREATE TABLE `cw_county` (
  `idcounty` int(11) NOT NULL,
  `countyName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cw_county`
--

INSERT INTO `cw_county` (`idcounty`, `countyName`) VALUES
(1, 'Antrim'),
(2, 'Armagh'),
(3, 'Carlow'),
(4, 'Cavan'),
(5, 'Clare'),
(6, 'Cork'),
(7, 'Donegal'),
(8, 'Down'),
(9, 'Dublin'),
(10, 'DunLaoghaire-Rathdown'),
(11, 'Fermanagh'),
(12, 'Fingal'),
(13, 'Galway'),
(14, 'Kerry'),
(15, 'Kildare'),
(16, 'Kilkenny'),
(17, 'Laois'),
(18, 'Leitrim'),
(19, 'Limerick'),
(20, 'Londonderry'),
(21, 'Longford'),
(22, 'Louth'),
(23, 'Mayo'),
(24, 'Meath'),
(25, 'Monaghan'),
(26, 'North Tipperary'),
(27, 'Offaly'),
(28, 'Roscommon'),
(29, 'Sligo'),
(30, 'South Dublin'),
(31, 'South Tipperary'),
(32, 'Tipperary'),
(33, 'Tyrone'),
(34, 'Waterford'),
(35, 'Westmeath'),
(36, 'Wexford'),
(37, 'Wicklow'),
(99, 'Unknown County');

-- --------------------------------------------------------

--
-- Table structure for table `cw_patrol_roster`
--

CREATE TABLE `cw_patrol_roster` (
  `volunteer_ID_Nr` int(11) NOT NULL,
  `patrolNr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cw_patrol_roster`
--

INSERT INTO `cw_patrol_roster` (`volunteer_ID_Nr`, `patrolNr`) VALUES
(2, 0),
(5, 0),
(6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cw_patrol_schedule`
--

CREATE TABLE `cw_patrol_schedule` (
  `patrolNr` int(11) NOT NULL,
  `patrolDate` date DEFAULT NULL,
  `patrolDescription` varchar(45) DEFAULT 'Regular Scheduled Patrol',
  `SuperUserNr` int(11) DEFAULT NULL,
  `patrol_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cw_patrol_schedule`
--

INSERT INTO `cw_patrol_schedule` (`patrolNr`, `patrolDate`, `patrolDescription`, `SuperUserNr`, `patrol_status`) VALUES
(1, '2025-11-23', 'Regular Scheduled Patrol', 42, 0),
(2, '2025-12-24', 'Regular Scheduled Patrol', 44, 0),
(3, '2026-01-30', 'Regular Scheduled Patrol', 42, 0),
(4, '2026-01-31', 'Regular Scheduled Patrol', 44, 0),
(5, '2026-02-06', 'Regular Scheduled Patrol', 42, 0),
(6, '2026-02-07', 'Regular Scheduled Patrol', 46, 0),
(7, '2026-02-13', 'Regular Scheduled Patrol', 46, 0),
(8, '2026-02-14', 'Regular Scheduled Patrol', 46, 0),
(9, '2026-02-20', 'Regular Scheduled Patrol', 46, 0),
(10, '2026-02-21', 'Regular Scheduled Patrol', 46, 0),
(11, '2026-02-27', 'Regular Scheduled Patrol', 46, 0),
(12, '2026-02-28', 'Regular Scheduled Patrol', 46, 0),
(13, '2026-03-06', 'Regular Scheduled Patrol', 46, 0),
(14, '2026-03-07', 'Regular Scheduled Patrol', 46, 0),
(15, '2026-03-13', 'Regular Scheduled Patrol', 46, 0),
(16, '2026-03-14', 'Regular Scheduled Patrol', 46, 0),
(17, '2026-03-20', 'Regular Scheduled Patrol', 46, 0),
(18, '2026-03-21', 'Regular Scheduled Patrol', 46, 0),
(19, '2026-03-27', 'Regular Scheduled Patrol', 46, 0),
(20, '2026-03-28', 'Regular Scheduled Patrol', 46, 0),
(21, '2026-04-03', 'Regular Scheduled Patrol', 46, 0),
(22, '2026-04-04', 'Regular Scheduled Patrol', 46, 0),
(23, '2026-04-10', 'Regular Scheduled Patrol', 46, 0),
(24, '2026-04-11', 'Regular Scheduled Patrol', 46, 0),
(25, '2026-04-17', 'Regular Scheduled Patrol', 46, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cw_patrol_status`
--

CREATE TABLE `cw_patrol_status` (
  `patrol_status_nr` int(11) NOT NULL,
  `patrol_status_description` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cw_patrol_status`
--

INSERT INTO `cw_patrol_status` (`patrol_status_nr`, `patrol_status_description`) VALUES
(0, 'Not Released'),
(1, 'Released for Rostering'),
(2, 'Suspended'),
(3, 'Postponed'),
(4, 'Roster Finalised');

-- --------------------------------------------------------

--
-- Table structure for table `cw_user`
--

CREATE TABLE `cw_user` (
  `UserNr` int(11) NOT NULL,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  `PassWord` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `userID` varchar(45) DEFAULT NULL,
  `userEnabled` tinyint(4) DEFAULT 1,
  `userTypeNr` int(11) NOT NULL DEFAULT 99,
  `idcounty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cw_user`
--

INSERT INTO `cw_user` (`UserNr`, `FirstName`, `LastName`, `PassWord`, `email`, `mobile`, `userID`, `userEnabled`, `userTypeNr`, `idcounty`) VALUES
(1, 'Gerry', 'Guinane', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'gerryguinane@claddaghwatch.ie', '0875869745', 'gerryguinane@claddaghwatch.ie', 1, 1, NULL),
(2, 'Jane', 'Murphy', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'janeh@mail.com', '0871234567', 'janeh@mail.com', 1, 3, NULL),
(3, 'Harry', 'Boland', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'harry@lit.ie', '01234567', 'harry@lit.ie', 1, 3, NULL),
(4, 'Deborah', 'Carr', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'deborahcarr@claddaghwatch.ie', '0875426987', 'deborahcarr@claddaghwatch.ie', 1, 3, NULL),
(5, 'James', 'Murphy', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'james@framework.com', '0862356897', 'james@framework.com', 1, 3, NULL),
(6, 'Jack', 'McKeown', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'jack@lit.ie', '0875458745', 'jack@lit.ie', 1, 3, NULL),
(25, 'elvis', 'presley', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'presley@tus.ie', '0865478745', 'presley@tus.ie', 1, 3, NULL),
(42, 'Arthur', 'Carr', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'arthurcarr@claddaghwatch.ie', '085457854', 'arthurcarr@claddaghwatch.ie', 1, 3, NULL),
(44, 'Jimmy', 'O', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'jimmyo@claddaghwatch.ie', '0854789654', 'jimmyo@claddaghwatch.ie', 1, 3, NULL),
(45, 'John', 'Customer2', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'cust2@cust.com', '0587458745', 'cust2@cust.com', 1, 3, NULL),
(46, 'Not', 'Assigned', 'cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96', 'notassigned@claddaghwatch.ie', '000000', 'notassigned@claddaghwatch.ie', 0, 3, NULL),
(47, 'Marius', 'Constantin', '8RXyhLqP8X0GHtuHUa4urgk7GlPOp8xegrHGoK7Zb0c=', 'marius.c49@yahoo.com', NULL, 'marius.c49@yahoo.com', 1, 3, NULL),
(48, 'Admin', 'Admin', 'CvsAE42OczSOwf5B/T06j8vZAVayY7+leRug4JX0LPw=', 'Admin@gmail.com', NULL, 'Admin@gmail.com', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cw_usertype`
--

CREATE TABLE `cw_usertype` (
  `userTypeNr` int(11) NOT NULL,
  `userTypeDescr` varchar(45) NOT NULL DEFAULT 'UNKNOWN'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cw_usertype`
--

INSERT INTO `cw_usertype` (`userTypeNr`, `userTypeDescr`) VALUES
(1, 'ADMIN'),
(2, 'MANAGER'),
(3, 'VOLUNTEER'),
(4, 'SUPER'),
(99, 'UNKNOWN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cw_county`
--
ALTER TABLE `cw_county`
  ADD PRIMARY KEY (`idcounty`);

--
-- Indexes for table `cw_patrol_roster`
--
ALTER TABLE `cw_patrol_roster`
  ADD PRIMARY KEY (`volunteer_ID_Nr`,`patrolNr`),
  ADD KEY `fk_patrol_schedule_has_user_user1_idx` (`volunteer_ID_Nr`),
  ADD KEY `fk_cw_patrol_roster_cw_patrol_schedule1_idx` (`patrolNr`);

--
-- Indexes for table `cw_patrol_schedule`
--
ALTER TABLE `cw_patrol_schedule`
  ADD PRIMARY KEY (`patrolNr`),
  ADD KEY `fk_patrol_schedule_user1_idx` (`SuperUserNr`),
  ADD KEY `fk_cw_patrol_schedule_cw_patrol_status1_idx` (`patrol_status`);

--
-- Indexes for table `cw_patrol_status`
--
ALTER TABLE `cw_patrol_status`
  ADD PRIMARY KEY (`patrol_status_nr`);

--
-- Indexes for table `cw_user`
--
ALTER TABLE `cw_user`
  ADD PRIMARY KEY (`UserNr`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `userID_UNIQUE` (`userID`),
  ADD KEY `fk_cw_user_cw_usertype1_idx` (`userTypeNr`),
  ADD KEY `fk_cw_user_cw_county1_idx` (`idcounty`);

--
-- Indexes for table `cw_usertype`
--
ALTER TABLE `cw_usertype`
  ADD PRIMARY KEY (`userTypeNr`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cw_county`
--
ALTER TABLE `cw_county`
  MODIFY `idcounty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `cw_patrol_schedule`
--
ALTER TABLE `cw_patrol_schedule`
  MODIFY `patrolNr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cw_user`
--
ALTER TABLE `cw_user`
  MODIFY `UserNr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cw_patrol_roster`
--
ALTER TABLE `cw_patrol_roster`
  ADD CONSTRAINT `fk_cw_patrol_roster_cw_patrol_schedule1` FOREIGN KEY (`patrolNr`) REFERENCES `cw_patrol_schedule` (`patrolNr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_patrol_schedule_has_user_user1` FOREIGN KEY (`volunteer_ID_Nr`) REFERENCES `cw_user` (`UserNr`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cw_patrol_schedule`
--
ALTER TABLE `cw_patrol_schedule`
  ADD CONSTRAINT `fk_cw_patrol_schedule_cw_patrol_status1` FOREIGN KEY (`patrol_status`) REFERENCES `cw_patrol_status` (`patrol_status_nr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_patrol_schedule_user1` FOREIGN KEY (`SuperUserNr`) REFERENCES `cw_user` (`UserNr`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cw_user`
--
ALTER TABLE `cw_user`
  ADD CONSTRAINT `fk_cw_user_cw_county1` FOREIGN KEY (`idcounty`) REFERENCES `cw_county` (`idcounty`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cw_user_cw_usertype1` FOREIGN KEY (`userTypeNr`) REFERENCES `cw_usertype` (`userTypeNr`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
