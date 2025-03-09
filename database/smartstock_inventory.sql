-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306:3308
-- Generation Time: Mar 08, 2025 at 12:07 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`` PROCEDURE `AddProduct` (IN `p_Name` VARCHAR(255), IN `p_CategoryID` INT, IN `p_CreatedBy` INT)   BEGIN
    INSERT INTO products (Name, CategoryID, StockQuantity, Price, Status, Created_At, Created_By)
    VALUES (p_Name, p_CategoryID, 0, 0.00, 'Out of Stock', NOW(), p_CreatedBy);
END$$

CREATE DEFINER=`` PROCEDURE `DiscardStock` (IN `p_AdminID` INT, IN `p_ProductID` INT, IN `p_Reason` VARCHAR(255), IN `p_Quantity` INT)   BEGIN
    DECLARE adjustmentID INT;

    -- Start transaction
    START TRANSACTION;

    -- Insert into Adjustments table
    INSERT INTO Adjustments (AdminID, Reason, AdjustmentDate, Created_at, Created_by)
    VALUES (p_AdminID, p_Reason, NOW(), NOW(), p_AdminID);
    
    SET adjustmentID = LAST_INSERT_ID();

    -- Insert into Adjustment_details table
    INSERT INTO Adjustment_details (ProductID, AdjustmentID, AdjustmentType, QuantityAdjusted)
    VALUES (p_ProductID, adjustmentID, p_Reason, p_Quantity);

    -- Reduce stock quantity in Products table
    UPDATE Products SET StockQuantity = StockQuantity - p_Quantity WHERE ProductID = p_ProductID;

    -- Commit transaction
    COMMIT;
END$$

CREATE DEFINER=`` PROCEDURE `GetDashboardStats` ()   BEGIN
    -- Total Sales (Completed Orders)
    SELECT COALESCE(SUM(total), 0) AS total_sales FROM orders WHERE Status = 'Paid';

    -- Total Orders
    SELECT COUNT(*) AS total_orders FROM orders;

    -- Total Products in Stock
    SELECT COALESCE(SUM(StockQuantity), 0) AS total_products FROM products;

    -- Low Stock Products (Stock < 5)
    SELECT COUNT(*) AS low_stock_products FROM products WHERE StockQuantity < 5;
END$$

CREATE DEFINER=`` PROCEDURE `GetProducts` (IN `filterType` VARCHAR(20))   BEGIN
    SELECT p.Name, p.ProductID, p.Price, s.Name AS SupplierName, 
           c.Name AS CategoryName, p.StockQuantity, p.Status 
    FROM products p
    LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID
    LEFT JOIN categories c ON p.CategoryID = c.CategoryID
    WHERE (filterType = 'All') OR 
          (filterType = 'In Stock' AND p.Status = 'In Stock') OR 
          (filterType = 'Out of Stock' AND p.Status = 'Out of Stock')
    ORDER BY p.Name ASC;
    
END$$

CREATE DEFINER=`` PROCEDURE `GetSalesByCategory` ()   BEGIN
    SELECT c.Name AS CategoryName, SUM(o.Total) AS TotalSales
    FROM orders o
    JOIN orderline ol ON o.OrderID = ol.OrderID
    JOIN products p ON ol.ProductID = p.ProductID
    JOIN categories c ON p.CategoryID = c.CategoryID
    GROUP BY c.Name
    ORDER BY TotalSales DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `adjustments`
--

CREATE TABLE `adjustments` (
  `AdjustmentID` int(11) NOT NULL,
  `AdminID` int(11) DEFAULT NULL,
  `Reason` text NOT NULL,
  `AdjustmentDate` datetime NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adjustments`
--

INSERT INTO `adjustments` (`AdjustmentID`, `AdminID`, `Reason`, `AdjustmentDate`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(1, 1, 'Expired', '2025-03-08 08:05:34', '2025-03-08 08:05:34', 1, '2025-03-08 08:05:34', 0),
(2, 1, 'Expired', '2025-03-08 08:09:37', '2025-03-08 08:09:37', 1, '2025-03-08 08:09:37', 0),
(3, 1, 'Expired', '2025-03-08 08:11:39', '2025-03-08 08:11:39', 1, '2025-03-08 08:11:39', 0),
(4, 1, 'Expired', '2025-03-08 08:13:09', '2025-03-08 08:13:09', 1, '2025-03-08 08:13:09', 0),
(5, 1, 'Expired', '2025-03-08 08:18:21', '2025-03-08 08:18:21', 1, '2025-03-08 08:18:21', 0),
(6, 1, 'Expired', '2025-03-08 08:34:32', '2025-03-08 08:34:32', 1, '2025-03-08 08:34:32', 0),
(7, 1, 'Expired', '2025-03-08 09:20:37', '2025-03-08 09:20:37', 1, '2025-03-08 09:20:37', 0),
(8, 1, 'Expired', '2025-03-08 09:21:51', '2025-03-08 09:21:51', 1, '2025-03-08 09:21:51', 0),
(9, 1, 'Expired', '2025-03-08 09:22:42', '2025-03-08 09:22:42', 1, '2025-03-08 09:22:42', 0),
(10, 1, 'Expired', '2025-03-08 16:59:39', '2025-03-08 16:59:39', 1, '2025-03-08 16:59:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `adjustment_details`
--

CREATE TABLE `adjustment_details` (
  `AdjustmentDetailID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `AdjustmentID` int(11) DEFAULT NULL,
  `AdjustmentType` varchar(50) NOT NULL,
  `QuantityAdjusted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adjustment_details`
--

INSERT INTO `adjustment_details` (`AdjustmentDetailID`, `ProductID`, `AdjustmentID`, `AdjustmentType`, `QuantityAdjusted`) VALUES
(1, 0, 1, 'Expired', 4),
(2, 0, 2, 'Expired', 4),
(3, 0, 3, 'Expired', 4),
(4, 0, 4, 'Expired', 4),
(5, 0, 5, 'Expired', 4),
(6, 14, 6, 'Expired', 4),
(7, 15, 7, 'Expired', 10),
(8, 12, 8, 'Expired', 5),
(9, 10, 9, 'Expired', 5),
(10, 16, 10, 'Expired', 5);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` text DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `Name`, `Description`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(8, 'Beverages', '- Soft Drinks\r\n- Coffee & Tea\r\n- Powdered Juice & Energy Drinks\r\n- Bottled Water\r\n- Alcoholic Drinks (if permitted)', '2025-03-04 09:27:45', 1, '2025-03-06 14:51:51', 1),
(10, 'Food & Snacks', '- Canned Goods (e.g., sardines, corned beef, meatloaf)\r\n- Instant Noodles & Pasta\r\n- Rice & Grains\r\n- Bread & Bakery Products\r\n- Chips & Junk Food\r\n- Biscuits & Crackers\r\n- Chocolates & Candies', '2025-03-06 14:51:09', 1, '2025-03-06 14:51:09', 1),
(11, 'Frozen & Chilled Products', '- Hotdogs & Sausages\r\n- Frozen Meat & Fish\r\n- Ice Cream & Frozen Desserts\r\n- Ice Cubes', '2025-03-06 14:52:43', 1, '2025-03-06 14:52:43', 1),
(12, 'Condiments & Cooking Essentials', '- Cooking Oil\r\n- Soy Sauce, Vinegar, Fish Sauce\r\n- Salt, Sugar, & Other Spices\r\n- Powdered & Liquid Seasonings', '2025-03-06 14:53:07', 1, '2025-03-06 14:53:07', 1),
(13, 'Household Essentials', '- Detergents & Fabric Softeners\r\n- Dishwashing Liquids & Sponges\r\n- Cleaning Agents (e.g., bleach, floor wax)\r\n- Plastic Bags & Garbage Bags', '2025-03-06 14:53:31', 1, '2025-03-06 14:53:31', 1),
(14, 'Personal Care & Hygiene', '- Shampoo & Conditioner\r\n- Soap & Body Wash\r\n- Toothpaste & Toothbrush\r\n- Deodorants & Powders\r\n- Feminine Hygiene Products\r\n- Baby Products (e.g., diapers, wipes)', '2025-03-06 14:53:58', 1, '2025-03-06 14:53:58', 1),
(15, 'School & Office Supplies', '- Notebooks & Papers\r\n- Pens, Pencils, & Markers\r\n- Glue, Tape, & Scissors\r\n- Envelopes & Folders', '2025-03-06 14:54:22', 1, '2025-03-06 14:54:22', 1),
(16, 'Miscellaneous Items', '- Candles & Matches\r\n- Batteries\r\n- Light Bulbs\r\n- Small Toys', '2025-03-06 14:55:11', 1, '2025-03-06 14:55:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `PhoneNumber` varchar(255) DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `Name`, `Address`, `PhoneNumber`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(2, 'Customer', 'Sample Address', '09123456789', '2025-03-07 18:55:11', 1, '2025-03-07 18:55:11', 1),
(3, 'Customer', 'Sample Address', '09123456789', '2025-03-07 19:00:35', 1, '2025-03-07 19:00:35', 1),
(4, 'Customer', 'Sample Address', '09123456789', '2025-03-07 19:06:15', 1, '2025-03-07 19:06:15', 1),
(5, 'Customer', 'Sample Address', '09123456789', '2025-03-07 19:09:39', 1, '2025-03-07 19:09:39', 1),
(6, 'Customer', 'Sample Address', '09123456789', '2025-03-07 19:11:38', 1, '2025-03-07 19:11:38', 1),
(7, 'Customer', 'Sample Address', '09123456789', '2025-03-07 19:34:45', 1, '2025-03-07 19:34:45', 1),
(8, 'Customer', 'Sample Address', '09123456789', '2025-03-07 19:40:41', 1, '2025-03-07 19:40:41', 1),
(9, 'Jeriel Sanao', 'Calinan', '09123456789', '2025-03-08 09:33:34', 1, '2025-03-08 09:33:34', 1),
(10, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:24', 1, '2025-03-08 17:16:24', 1),
(11, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:26', 1, '2025-03-08 17:16:26', 1),
(12, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:27', 1, '2025-03-08 17:16:27', 1),
(13, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:27', 1, '2025-03-08 17:16:27', 1),
(14, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:27', 1, '2025-03-08 17:16:27', 1),
(15, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:27', 1, '2025-03-08 17:16:27', 1),
(16, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:27', 1, '2025-03-08 17:16:27', 1),
(17, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:28', 1, '2025-03-08 17:16:28', 1),
(18, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:28', 1, '2025-03-08 17:16:28', 1),
(19, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:28', 1, '2025-03-08 17:16:28', 1),
(20, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:28', 1, '2025-03-08 17:16:28', 1),
(21, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:28', 1, '2025-03-08 17:16:28', 1),
(22, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:28', 1, '2025-03-08 17:16:28', 1),
(23, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:29', 1, '2025-03-08 17:16:29', 1),
(24, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:29', 1, '2025-03-08 17:16:29', 1),
(25, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:29', 1, '2025-03-08 17:16:29', 1),
(26, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:29', 1, '2025-03-08 17:16:29', 1),
(27, 'Jeriel', 'Calinan', '09123456789', '2025-03-08 17:16:29', 1, '2025-03-08 17:16:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` char(15) NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) DEFAULT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`EmployeeID`, `FirstName`, `LastName`, `Username`, `Password`, `Role`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(1, 'Junits', 'Coretico', 'junitsstore_admin', '$2y$10$WfGbACvKiCJM40LAMkFAf.AYENhhGddZSszrlFeIYGJjF8MdbMc3S', 'admin', '2025-03-01 15:01:46', NULL, '2025-03-01 16:51:17', 1),
(2, 'Jeriel', 'Sanao', 'jerielsanao1', '$2y$10$gNysNXP63PW21vIkuqMjJufdCDApTJDs2BsIpJ6uOo8lb3sPxSASG', 'Employee', '2025-03-01 15:46:32', 1, '2025-03-01 15:46:32', 1),
(3, 'Russel', 'Labiaga', 'russelito1', '$2y$10$lIpDauqYu1cmpxxkehhxiOuxtmj269dcygSV4TW2omlbEiAPG6i5O', 'Employee', '2025-03-01 16:03:13', 1, '2025-03-01 16:03:13', 1),
(4, 'Jeriel', 'Sanao', 'jerielsanao27', '$2y$10$Cn0TZh6.wg9Gnwas.gCQcO6CUz4VpfyNox/dT8V2t/VRCsl9w7.0.', 'Employee', '2025-03-01 16:39:57', 1, '2025-03-01 16:56:23', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

CREATE TABLE `orderline` (
  `OrderLineID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`OrderLineID`, `ProductID`, `OrderID`, `Quantity`, `Price`) VALUES
(2, 14, 2, 1, 55.00),
(3, 12, 2, 1, 21.00),
(4, 12, 3, 4, 21.00),
(5, 11, 4, 5, 21.00),
(6, 15, 4, 2, 25.00),
(7, 16, 4, 3, 20.00),
(8, 11, 5, 5, 21.00),
(9, 15, 5, 2, 25.00),
(10, 16, 5, 3, 20.00),
(11, 16, 5, 10, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `Date` datetime NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `Status` char(5) NOT NULL,
  `Delivery` int(11) DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `Date`, `Total`, `Status`, `Delivery`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(1, 2, '2025-03-07 21:44:24', 55.00, 'Paid', 0, '2025-03-07 21:44:24', 1, '2025-03-07 21:44:24', 1),
(2, 2, '2025-03-07 21:50:26', 76.00, 'Paid', 0, '2025-03-07 21:50:26', 1, '2025-03-07 21:50:26', 1),
(3, 2, '2025-03-07 21:57:27', 84.00, 'Paid', 0, '2025-03-07 21:57:27', 1, '2025-03-07 21:57:27', 1),
(4, 10, '2025-03-08 17:17:47', 215.00, 'Paid', 0, '2025-03-08 17:17:47', 1, '2025-03-08 17:17:47', 1),
(5, 2, '2025-03-08 17:19:13', 415.00, 'Paid', 0, '2025-03-08 17:19:13', 1, '2025-03-08 17:19:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `StockQuantity` int(11) NOT NULL,
  `Status` enum('In Stock','Out of Stock') NOT NULL DEFAULT 'In Stock',
  `SupplierID` int(11) NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `Name`, `CategoryID`, `Price`, `StockQuantity`, `Status`, `SupplierID`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(10, 'Mountain Dew', 8, 23.00, 345, 'In Stock', 2, '2025-03-06 14:44:16', 1, '2025-03-08 09:22:42', 1),
(11, 'Coca-cola', 8, 21.00, 230, 'In Stock', 2, '2025-03-06 14:44:24', 1, '2025-03-08 17:19:13', 1),
(12, 'Sprite', 8, 21.00, 230, 'In Stock', 2, '2025-03-06 14:44:34', 1, '2025-03-08 09:21:51', 1),
(13, 'C2 (Red)', 8, 21.00, 255, 'In Stock', 2, '2025-03-06 14:44:59', 1, '2025-03-06 21:25:22', 1),
(14, 'Chicken Nuggets', 11, 55.00, 55, 'In Stock', 3, '2025-03-06 21:34:37', 1, '2025-03-08 08:34:32', 0),
(15, 'Intermediate Paper', 15, 25.00, 66, 'In Stock', 3, '2025-03-06 21:37:02', 1, '2025-03-08 17:19:13', 0),
(16, 'Argentina Beef Loaf', 10, 20.00, 29, 'In Stock', 3, '2025-03-06 21:39:20', 1, '2025-03-08 17:19:13', 1),
(17, 'Colgate', 14, 0.00, 0, 'Out of Stock', 0, '2025-03-08 09:18:58', 1, '2025-03-08 09:18:58', 0),
(18, 'Kagayaku Soap', 14, 0.00, 0, 'Out of Stock', 0, '2025-03-08 16:50:45', 1, '2025-03-08 16:50:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `receiving`
--

CREATE TABLE `receiving` (
  `ReceivingID` int(11) NOT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `Date` datetime NOT NULL,
  `Status` varchar(20) DEFAULT 'Pending',
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receiving`
--

INSERT INTO `receiving` (`ReceivingID`, `SupplierID`, `Date`, `Status`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(8, 2, '2025-03-06 00:00:00', 'Received', '2025-03-06 07:45:53', 1, '2025-03-06 15:20:04', 1),
(9, 3, '2025-03-06 00:00:00', 'Received', '2025-03-06 14:41:22', 1, '0000-00-00 00:00:00', 1),
(10, 2, '2025-03-06 00:00:00', 'Received', '2025-03-06 14:42:30', 1, '2025-03-06 21:42:42', 1),
(11, 3, '2025-03-06 00:00:00', 'Received', '2025-03-06 14:48:29', 1, '2025-03-06 21:48:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `receiving_details`
--

CREATE TABLE `receiving_details` (
  `ReceivingDetailID` int(11) NOT NULL,
  `ReceivingID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitCost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receiving_details`
--

INSERT INTO `receiving_details` (`ReceivingDetailID`, `ReceivingID`, `ProductID`, `Quantity`, `UnitCost`) VALUES
(5, 8, 10, 120, 23.00),
(6, 8, 11, 120, 21.00),
(7, 8, 12, 120, 21.00),
(8, 8, 13, 120, 21.00),
(9, 9, 16, 50, 20.00),
(10, 9, 14, 60, 55.00),
(11, 10, 15, 30, 25.00),
(12, 11, 15, 50, 25.00);

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `ReturnID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ReturnDate` datetime NOT NULL,
  `Reason` text DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`ReturnID`, `CustomerID`, `OrderID`, `ReturnDate`, `Reason`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(1, 1, 1, '2025-03-05 00:00:00', 'Expired', '2025-03-05 15:22:35', 1, '2025-03-05 15:22:35', 1),
(2, 9, 4, '2025-03-08 00:00:00', 'Expired', '2025-03-08 09:33:34', 1, '2025-03-08 09:33:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `returntosupplier`
--

CREATE TABLE `returntosupplier` (
  `ReturnSupplierID` int(11) NOT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `ReturnDate` datetime NOT NULL,
  `Reason` text NOT NULL,
  `Status` int(11) NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returntosupplier`
--

INSERT INTO `returntosupplier` (`ReturnSupplierID`, `SupplierID`, `ReturnDate`, `Reason`, `Status`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(1, 2, '2025-03-08 09:13:11', 'Expired', 0, '2025-03-08 09:13:11', 1, '2025-03-08 09:13:11', 0),
(2, 2, '2025-03-08 09:14:03', 'Expired', 0, '2025-03-08 09:14:03', 1, '2025-03-08 09:14:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `returntosupplierdetails`
--

CREATE TABLE `returntosupplierdetails` (
  `ReturnSupplierDetailID` int(11) NOT NULL,
  `ReturnSupplierID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `QuantityReturned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returntosupplierdetails`
--

INSERT INTO `returntosupplierdetails` (`ReturnSupplierDetailID`, `ReturnSupplierID`, `ProductID`, `QuantityReturned`) VALUES
(1, 1, 10, 5),
(2, 2, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `return_details`
--

CREATE TABLE `return_details` (
  `ReturnDetailID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `ReturnID` int(11) DEFAULT NULL,
  `QuantityReturned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `ProfileImage` varchar(255) DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierID`, `Name`, `Address`, `PhoneNumber`, `ProfileImage`, `Created_At`, `Created_By`, `Updated_At`, `Updated_By`) VALUES
(2, 'Supplier2', 'supplier2, address', '0912345689', NULL, '2025-03-03 14:29:33', 1, '2025-03-03 14:29:33', 1),
(3, 'Supplier3', 'Mintal, Davao City', '09123456789', NULL, '2025-03-06 20:10:31', 1, '2025-03-06 20:10:31', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `supplier_order_view`
-- (See below for the actual view)
--
CREATE TABLE `supplier_order_view` (
`ReceivingDetailID` int(11)
,`product_name` varchar(50)
,`product_quantity` int(11)
,`unit_cost` decimal(10,2)
,`total_cost` decimal(20,2)
,`order_date` datetime
,`supplier_id` int(11)
,`supplier_name` varchar(50)
,`order_status` varchar(20)
);

-- --------------------------------------------------------

--
-- Structure for view `supplier_order_view`
--
DROP TABLE IF EXISTS `supplier_order_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `supplier_order_view`  AS SELECT `rd`.`ReceivingDetailID` AS `ReceivingDetailID`, `p`.`Name` AS `product_name`, `rd`.`Quantity` AS `product_quantity`, `rd`.`UnitCost` AS `unit_cost`, `rd`.`Quantity`* `rd`.`UnitCost` AS `total_cost`, `r`.`Date` AS `order_date`, `s`.`SupplierID` AS `supplier_id`, `s`.`Name` AS `supplier_name`, `r`.`Status` AS `order_status` FROM (((`receiving_details` `rd` join `receiving` `r` on(`rd`.`ReceivingID` = `r`.`ReceivingID`)) join `products` `p` on(`rd`.`ProductID` = `p`.`ProductID`)) join `suppliers` `s` on(`r`.`SupplierID` = `s`.`SupplierID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adjustments`
--
ALTER TABLE `adjustments`
  ADD PRIMARY KEY (`AdjustmentID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `adjustment_details`
--
ALTER TABLE `adjustment_details`
  ADD PRIMARY KEY (`AdjustmentDetailID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `AdjustmentID` (`AdjustmentID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD KEY `employees_ibfk_1` (`Created_By`),
  ADD KEY `employees_ibfk_2` (`Updated_By`);

--
-- Indexes for table `orderline`
--
ALTER TABLE `orderline`
  ADD PRIMARY KEY (`OrderLineID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `receiving`
--
ALTER TABLE `receiving`
  ADD PRIMARY KEY (`ReceivingID`),
  ADD KEY `SupplierID` (`SupplierID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `receiving_details`
--
ALTER TABLE `receiving_details`
  ADD PRIMARY KEY (`ReceivingDetailID`),
  ADD KEY `ReceivingID` (`ReceivingID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`ReturnID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `returntosupplier`
--
ALTER TABLE `returntosupplier`
  ADD PRIMARY KEY (`ReturnSupplierID`),
  ADD KEY `SupplierID` (`SupplierID`),
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `returntosupplierdetails`
--
ALTER TABLE `returntosupplierdetails`
  ADD PRIMARY KEY (`ReturnSupplierDetailID`),
  ADD KEY `ReturnSupplierID` (`ReturnSupplierID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `return_details`
--
ALTER TABLE `return_details`
  ADD PRIMARY KEY (`ReturnDetailID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `ReturnID` (`ReturnID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierID`),
  ADD UNIQUE KEY `Name` (`Name`),
  ADD UNIQUE KEY `PhoneNumber` (`PhoneNumber`),
  ADD UNIQUE KEY `Address` (`Address`) USING HASH,
  ADD KEY `Created_By` (`Created_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adjustments`
--
ALTER TABLE `adjustments`
  MODIFY `AdjustmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `adjustment_details`
--
ALTER TABLE `adjustment_details`
  MODIFY `AdjustmentDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orderline`
--
ALTER TABLE `orderline`
  MODIFY `OrderLineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `receiving`
--
ALTER TABLE `receiving`
  MODIFY `ReceivingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `receiving_details`
--
ALTER TABLE `receiving_details`
  MODIFY `ReceivingDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `ReturnID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `returntosupplier`
--
ALTER TABLE `returntosupplier`
  MODIFY `ReturnSupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `returntosupplierdetails`
--
ALTER TABLE `returntosupplierdetails`
  MODIFY `ReturnSupplierDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `return_details`
--
ALTER TABLE `return_details`
  MODIFY `ReturnDetailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`Created_By`) REFERENCES `employees` (`EmployeeID`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`Updated_By`) REFERENCES `employees` (`EmployeeID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
