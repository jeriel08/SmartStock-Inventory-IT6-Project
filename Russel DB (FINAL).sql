-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 01:35 PM
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
-- Database: `smartstock_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `PhoneNumber` varchar(255) DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `Name`, `PhoneNumber`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(71, 'Russel Labiaga', '09770796010', '2025-03-12 11:04:42', 1, '2025-03-12 11:04:42', 1),
(72, 'Paula', '5311351', '2025-03-12 19:48:43', 1, '2025-03-12 19:48:43', 1),
(73, 'May', '1919191919', '2025-03-14 16:48:51', 1, '2025-03-14 16:48:51', 1),
(74, 'Legiana', '6246125', '2025-03-14 17:42:56', 1, '2025-03-14 17:42:56', 1),
(75, 'Spongebob', '94573673', '2025-03-14 17:55:12', 1, '2025-03-14 17:55:12', 1),
(76, 'Final Test', '09770796010', '2025-03-14 20:34:25', 1, '2025-03-14 20:34:25', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
