-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306:3308
-- Generation Time: Mar 10, 2025 at 02:25 AM
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
-- Structure for view `supplier_order_view`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `supplier_order_view`  AS SELECT `rd`.`ReceivingDetailID` AS `ReceivingDetailID`, `p`.`Name` AS `product_name`, `rd`.`Quantity` AS `product_quantity`, `rd`.`UnitCost` AS `unit_cost`, `rd`.`Quantity`* `rd`.`UnitCost` AS `total_cost`, `r`.`Date` AS `order_date`, `s`.`SupplierID` AS `supplier_id`, `s`.`Name` AS `supplier_name`, `r`.`Status` AS `order_status` FROM (((`receiving_details` `rd` join `receiving` `r` on(`rd`.`ReceivingID` = `r`.`ReceivingID`)) join `products` `p` on(`rd`.`ProductID` = `p`.`ProductID`)) join `suppliers` `s` on(`r`.`SupplierID` = `s`.`SupplierID`)) ;

--
-- VIEW `supplier_order_view`
-- Data: None
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
