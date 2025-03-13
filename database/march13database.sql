-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: smartstock_inventory
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adjustment_details`
--

DROP TABLE IF EXISTS `adjustment_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adjustment_details` (
  `AdjustmentDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `AdjustmentID` int(11) DEFAULT NULL,
  `AdjustmentType` varchar(50) NOT NULL,
  `QuantityAdjusted` int(11) NOT NULL,
  PRIMARY KEY (`AdjustmentDetailID`),
  KEY `ProductID` (`ProductID`),
  KEY `AdjustmentID` (`AdjustmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adjustment_details`
--

LOCK TABLES `adjustment_details` WRITE;
/*!40000 ALTER TABLE `adjustment_details` DISABLE KEYS */;
INSERT INTO `adjustment_details` VALUES (1,0,1,'Expired',4),(2,0,2,'Expired',4),(3,0,3,'Expired',4),(4,0,4,'Expired',4),(5,0,5,'Expired',4),(6,14,6,'Expired',4),(7,15,7,'Expired',10),(8,12,8,'Expired',5),(9,10,9,'Expired',5),(10,16,10,'Expired',5),(11,16,16,'Expired',4),(12,19,17,'Expired',4),(13,14,18,'Expired',3),(14,16,19,'Expired',1),(15,16,20,'Expired',1),(16,16,21,'Expired',1),(17,16,22,'Expired',2);
/*!40000 ALTER TABLE `adjustment_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adjustments`
--

DROP TABLE IF EXISTS `adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adjustments` (
  `AdjustmentID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminID` int(11) DEFAULT NULL,
  `Reason` text NOT NULL,
  `AdjustmentDate` datetime NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  PRIMARY KEY (`AdjustmentID`),
  KEY `AdminID` (`AdminID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adjustments`
--

LOCK TABLES `adjustments` WRITE;
/*!40000 ALTER TABLE `adjustments` DISABLE KEYS */;
INSERT INTO `adjustments` VALUES (1,1,'Damaged Stock','2025-03-09 16:19:56','2025-03-08 08:05:34',1,'2025-03-09 16:19:56',2),(2,1,'Expired','2025-03-08 08:09:37','2025-03-08 08:09:37',1,'2025-03-08 08:09:37',0),(3,1,'Expired','2025-03-08 08:11:39','2025-03-08 08:11:39',1,'2025-03-08 08:11:39',0),(4,1,'Expired','2025-03-08 08:13:09','2025-03-08 08:13:09',1,'2025-03-08 08:13:09',0),(5,1,'Expired','2025-03-08 08:18:21','2025-03-08 08:18:21',1,'2025-03-08 08:18:21',0),(6,1,'Expired','2025-03-08 08:34:32','2025-03-08 08:34:32',1,'2025-03-08 08:34:32',0),(7,1,'Expired','2025-03-08 09:20:37','2025-03-08 09:20:37',1,'2025-03-08 09:20:37',0),(8,1,'Expired','2025-03-08 09:21:51','2025-03-08 09:21:51',1,'2025-03-08 09:21:51',0),(9,1,'Expired','2025-03-08 09:22:42','2025-03-08 09:22:42',1,'2025-03-08 09:22:42',0),(10,1,'Expired','2025-03-08 16:59:39','2025-03-08 16:59:39',1,'2025-03-08 16:59:39',0),(16,1,'Expired','2025-03-09 15:18:59','2025-03-09 15:18:59',1,'2025-03-09 15:18:59',0),(17,1,'Expired','2025-03-09 15:24:12','2025-03-09 15:24:12',1,'2025-03-09 15:24:12',0),(18,1,'Expired','2025-03-09 15:43:40','2025-03-09 15:43:40',1,'2025-03-09 15:43:40',0),(19,1,'Expired','2025-03-09 15:46:20','2025-03-09 15:46:20',1,'2025-03-09 15:46:20',0),(20,1,'Expired','2025-03-09 15:53:26','2025-03-09 15:53:26',1,'2025-03-09 15:53:26',0),(21,1,'Expired','2025-03-09 15:54:15','2025-03-09 15:54:15',1,'2025-03-09 15:54:15',0),(22,1,'Expired','2025-03-09 16:18:56','2025-03-09 16:18:56',1,'2025-03-09 16:18:56',0);
/*!40000 ALTER TABLE `adjustments` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER adjustments_audit_insert

AFTER INSERT ON Adjustments

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, NewValue, AdminID, Timestamp)

    VALUES ('Adjustments', NEW.AdjustmentID, 'Added', 'AdjustmentDate', NEW.AdjustmentDate, NEW.Created_by, NOW());

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER adjustments_audit_update

AFTER UPDATE ON Adjustments

FOR EACH ROW

BEGIN

    -- Log only if Reason changes

    IF OLD.Reason <> NEW.Reason THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('Adjustments', NEW.AdjustmentID, 'Updated', 'Reason', OLD.Reason, NEW.Reason, NEW.Updated_by, NOW());

    END IF;



    -- Log only if AdjustmentDate changes

    IF OLD.AdjustmentDate <> NEW.AdjustmentDate THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('Adjustments', NEW.AdjustmentID, 'Updated', 'AdjustmentDate', OLD.AdjustmentDate, NEW.AdjustmentDate, NEW.Updated_by, NOW());

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `TableName` varchar(50) NOT NULL,
  `RecordID` int(11) NOT NULL,
  `ActionType` enum('Added','Updated','Login','Logout') DEFAULT NULL,
  `ColumnName` varchar(50) NOT NULL,
  `OldValue` text DEFAULT NULL,
  `NewValue` text NOT NULL,
  `AdminID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`LogID`),
  KEY `AdminID` (`AdminID`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (5,'Orders',9,'Added','OrderDate',NULL,'2025-03-09 16:14:54',1,'2025-03-09 08:14:54'),(6,'Adjustments',22,'Added','AdjustmentDate',NULL,'2025-03-09 16:18:56',1,'2025-03-09 08:18:56'),(7,'Adjustments',1,'Updated','Reason','Expired','Damaged Stock',2,'2025-03-09 08:19:56'),(8,'Adjustments',1,'Updated','AdjustmentDate','2025-03-08 08:05:34','2025-03-09 16:19:56',2,'2025-03-09 08:19:56'),(9,'Receiving',13,'Added','Date',NULL,'2025-03-09 00:00:00',1,'2025-03-09 08:24:09'),(10,'Receiving',13,'Updated','Status','Pending','Received',1,'2025-03-09 08:24:43'),(11,'Returns',3,'Added','ReturnDate',NULL,'2025-03-09 00:00:00',1,'2025-03-09 08:27:36'),(12,'Returns',1,'Updated','Reason','Expired','Wrong item received',2,'2025-03-09 08:28:39'),(13,'ReturnToSupplier',3,'Added','ReturnDate',NULL,'2025-03-09 16:30:38',1,'2025-03-09 08:30:38'),(14,'Orders',10,'Added','OrderDate',NULL,'2025-03-09 16:52:04',1,'2025-03-09 08:52:04'),(15,'Orders',11,'Added','OrderDate',NULL,'2025-03-10 09:53:22',1,'2025-03-10 01:53:22'),(16,'Orders',12,'Added','OrderDate',NULL,'2025-03-11 08:24:38',1,'2025-03-11 00:24:38'),(17,'Orders',13,'Added','OrderDate',NULL,'2025-03-11 08:26:14',1,'2025-03-11 00:26:14'),(18,'Receiving',14,'Added','Date',NULL,'2025-03-11 00:00:00',1,'2025-03-11 07:24:57'),(21,'ReturnToSupplier',6,'Added','ReturnDate',NULL,'2025-03-12 00:00:00',1,'2025-03-12 06:45:25'),(22,'ReturnToSupplier',7,'Added','ReturnDate',NULL,'2025-03-12 00:00:00',1,'2025-03-12 06:51:10'),(23,'ReturnToSupplier',8,'Added','ReturnDate',NULL,'2025-03-12 00:00:00',1,'2025-03-12 06:54:24'),(24,'Receiving',15,'Added','Date',NULL,'2025-03-12 00:00:00',1,'2025-03-12 06:59:40'),(25,'Receiving',15,'Updated','Status','Pending','Received',1,'2025-03-12 07:12:06'),(29,'returns',7,'','ReturnDate',NULL,'2023-10-01 00:00:00',1,'2025-03-12 13:46:28'),(31,'Returns',9,'','ReturnDate',NULL,'2025-03-12 00:00:00',1,'2025-03-12 13:51:27'),(32,'Returns',10,'Added','ReturnDate',NULL,'2025-03-12 00:00:00',1,'2025-03-12 14:08:11'),(33,'Receiving',16,'Added','Date',NULL,'2025-03-13 00:00:00',1,'2025-03-12 23:33:41'),(34,'Employees',9,'Added','',NULL,'',1,'2025-03-13 00:47:04'),(35,'Employees',9,'Updated','',NULL,'',1,'2025-03-13 00:48:42'),(37,'Employees',11,'Added','',NULL,'Employee: johndoe3',1,'2025-03-13 00:56:00'),(38,'Employees',8,'Updated','',NULL,'Employee: janedoe1',1,'2025-03-13 00:56:34'),(39,'Employees',1,'','',NULL,'junitsstore_admin',1,'2025-03-13 01:01:35'),(40,'Employees',1,'','',NULL,'junitsstore_admin',1,'2025-03-13 01:01:39'),(41,'Employees',1,'Logout','',NULL,'junitsstore_admin',1,'2025-03-13 01:02:41'),(42,'Employees',1,'Login','',NULL,'junitsstore_admin',1,'2025-03-13 01:02:46'),(43,'Employees',1,'Logout','',NULL,'junitsstore_admin',1,'2025-03-13 01:02:59'),(44,'Employees',5,'Login','',NULL,'jerielsanao2',5,'2025-03-13 01:03:08'),(45,'Employees',5,'Logout','',NULL,'jerielsanao2',5,'2025-03-13 01:03:13'),(46,'Employees',1,'Login','',NULL,'junitsstore_admin',1,'2025-03-13 01:03:17'),(47,'Employees',1,'Login','',NULL,'junitsstore_admin',1,'2025-03-13 03:30:44'),(48,'Employees',1,'Login','',NULL,'junitsstore_admin',1,'2025-03-13 06:18:39'),(49,'Returns',11,'Added','ReturnDate',NULL,'2025-03-13 00:00:00',1,'2025-03-13 06:53:53');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Description` text DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`CategoryID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (8,'Beverages','- Soft Drinks\r\n- Coffee & Tea\r\n- Powdered Juice & Energy Drinks\r\n- Bottled Water\r\n- Alcoholic Drinks (if permitted)','2025-03-04 09:27:45',1,'2025-03-11 12:29:44',1,'Active'),(10,'Food & Snacks','- Canned Goods (e.g., sardines, corned beef, meatloaf)\r\n- Instant Noodles & Pasta\r\n- Rice & Grains\r\n- Bread & Bakery Products\r\n- Chips & Junk Food\r\n- Biscuits & Crackers\r\n- Chocolates & Candies','2025-03-06 14:51:09',1,'2025-03-06 14:51:09',1,'Active'),(11,'Frozen & Chilled Products','- Hotdogs & Sausages\r\n- Frozen Meat & Fish\r\n- Ice Cream & Frozen Desserts\r\n- Ice Cubes','2025-03-06 14:52:43',1,'2025-03-06 14:52:43',1,'Active'),(12,'Condiments & Cooking Essentials','- Cooking Oil\r\n- Soy Sauce, Vinegar, Fish Sauce\r\n- Salt, Sugar, & Other Spices\r\n- Powdered & Liquid Seasonings','2025-03-06 14:53:07',1,'2025-03-06 14:53:07',1,'Active'),(13,'Household Essentials','- Detergents & Fabric Softeners\r\n- Dishwashing Liquids & Sponges\r\n- Cleaning Agents (e.g., bleach, floor wax)\r\n- Plastic Bags & Garbage Bags','2025-03-06 14:53:31',1,'2025-03-06 14:53:31',1,'Active'),(14,'Personal Care & Hygiene','- Shampoo & Conditioner\r\n- Soap & Body Wash\r\n- Toothpaste & Toothbrush\r\n- Deodorants & Powders\r\n- Feminine Hygiene Products\r\n- Baby Products (e.g., diapers, wipes)','2025-03-06 14:53:58',1,'2025-03-06 14:53:58',1,'Active'),(15,'School & Office Supplies','- Notebooks & Papers\r\n- Pens, Pencils, & Markers\r\n- Glue, Tape, & Scissors\r\n- Envelopes & Folders','2025-03-06 14:54:22',1,'2025-03-06 14:54:22',1,'Active'),(16,'Miscellaneous Items','- Candles & Matches\r\n- Batteries\r\n- Light Bulbs\r\n- Small Toys','2025-03-06 14:55:11',1,'2025-03-06 14:55:11',1,'Active');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `PhoneNumber` varchar(255) DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  PRIMARY KEY (`CustomerID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (2,'Customer','09123456789','2025-03-07 18:55:11',1,'2025-03-07 18:55:11',1),(3,'Customer','09123456789','2025-03-07 19:00:35',1,'2025-03-07 19:00:35',1),(4,'Customer','09123456789','2025-03-07 19:06:15',1,'2025-03-07 19:06:15',1),(5,'Customer','09123456789','2025-03-07 19:09:39',1,'2025-03-07 19:09:39',1),(6,'Customer','09123456789','2025-03-07 19:11:38',1,'2025-03-07 19:11:38',1),(7,'Customer','09123456789','2025-03-07 19:34:45',1,'2025-03-07 19:34:45',1),(8,'Customer','09123456789','2025-03-07 19:40:41',1,'2025-03-07 19:40:41',1),(9,'Jeriel Sanao','09123456789','2025-03-08 09:33:34',1,'2025-03-08 09:33:34',1),(10,'Jeriel','09123456789','2025-03-08 17:16:24',1,'2025-03-08 17:16:24',1),(11,'Jeriel','09123456789','2025-03-08 17:16:26',1,'2025-03-08 17:16:26',1),(12,'Jeriel','09123456789','2025-03-08 17:16:27',1,'2025-03-08 17:16:27',1),(13,'Jeriel','09123456789','2025-03-08 17:16:27',1,'2025-03-08 17:16:27',1),(14,'Jeriel','09123456789','2025-03-08 17:16:27',1,'2025-03-08 17:16:27',1),(15,'Jeriel','09123456789','2025-03-08 17:16:27',1,'2025-03-08 17:16:27',1),(16,'Jeriel','09123456789','2025-03-08 17:16:27',1,'2025-03-08 17:16:27',1),(17,'Jeriel','09123456789','2025-03-08 17:16:28',1,'2025-03-08 17:16:28',1),(18,'Jeriel','09123456789','2025-03-08 17:16:28',1,'2025-03-08 17:16:28',1),(19,'Jeriel','09123456789','2025-03-08 17:16:28',1,'2025-03-08 17:16:28',1),(20,'Jeriel','09123456789','2025-03-08 17:16:28',1,'2025-03-08 17:16:28',1),(21,'Jeriel','09123456789','2025-03-08 17:16:28',1,'2025-03-08 17:16:28',1),(22,'Jeriel','09123456789','2025-03-08 17:16:28',1,'2025-03-08 17:16:28',1),(23,'Jeriel','09123456789','2025-03-08 17:16:29',1,'2025-03-08 17:16:29',1),(24,'Jeriel','09123456789','2025-03-08 17:16:29',1,'2025-03-08 17:16:29',1),(25,'Jeriel','09123456789','2025-03-08 17:16:29',1,'2025-03-08 17:16:29',1),(26,'Jeriel','09123456789','2025-03-08 17:16:29',1,'2025-03-08 17:16:29',1),(27,'Jeriel','09123456789','2025-03-08 17:16:29',1,'2025-03-08 17:16:29',1);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `EmployeeID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` char(15) NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) DEFAULT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) DEFAULT NULL,
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`EmployeeID`),
  KEY `employees_ibfk_1` (`Created_By`),
  KEY `employees_ibfk_2` (`Updated_By`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`Created_By`) REFERENCES `employees` (`EmployeeID`) ON DELETE SET NULL,
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`Updated_By`) REFERENCES `employees` (`EmployeeID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Junits','Coretico','junitsstore_admin','$2y$10$WfGbACvKiCJM40LAMkFAf.AYENhhGddZSszrlFeIYGJjF8MdbMc3S','admin','2025-03-01 15:01:46',NULL,'2025-03-01 16:51:17',1,'Active'),(2,'Jeriel','Sanao','jerielsanao1','$2y$10$gNysNXP63PW21vIkuqMjJufdCDApTJDs2BsIpJ6uOo8lb3sPxSASG','Employee','2025-03-01 15:46:32',1,'2025-03-09 14:21:51',1,'Active'),(3,'Russel','Labiaga','russelito1','$2y$10$lIpDauqYu1cmpxxkehhxiOuxtmj269dcygSV4TW2omlbEiAPG6i5O','Employee','2025-03-01 16:03:13',1,'2025-03-01 16:03:13',1,'Active'),(4,'Jeriel','Sanao','jerielsanao27','$2y$10$Cn0TZh6.wg9Gnwas.gCQcO6CUz4VpfyNox/dT8V2t/VRCsl9w7.0.','Employee','2025-03-01 16:39:57',1,'2025-03-09 13:47:15',4,'Active'),(5,'Jeriel','Sanao','jerielsanao2','$2y$10$pNvA1CpVd4q.N7hJZkUyhOTa8xO7K.ZGTKq/to218Kd47/X2X1guS','Employee','2025-03-09 13:55:19',1,'2025-03-11 08:29:51',1,'Active'),(6,'John','Doe','sampleemp1','$2y$10$o8MTCeb0G66jCv483t/nCuLiA0uxun039/6DgLQeP1EWeWMTetl/6','Employee','2025-03-09 13:56:38',1,'2025-03-09 13:56:38',1,'Active'),(7,'John','Doe','johndoe1','$2y$10$HU45Cazu4itNPtkezxfoDurxtXfvMJvpOUqD/N6qCaG2XjYdmROt.','Employee','2025-03-11 14:46:50',1,'2025-03-13 08:06:28',NULL,'Inactive'),(8,'John','Doe','janedoe1','$2y$10$Io.TH2tUYk7zCYzSHLMtfOBOO2VKAGLF5SnDnnkJ.rFXuEUvvv376','Employee','2025-03-13 08:07:46',1,'2025-03-13 08:56:34',1,'Active'),(9,'John','Doe','johndoe22','$2y$10$xOFL2zYMiFUP8zaziyY48uR1qu7dw5ojY9Dukl2hyJbPvh/vtgd/i','Employee','2025-03-13 08:47:04',1,'2025-03-13 08:48:42',1,'Active'),(10,'John','Doe','johndoenew','$2y$10$i0CU9S0IvqV3QdpudUnXKeWnWelU5Vr/PZJeW/x514M5xRxmUNSOS','Employee','2025-03-13 08:52:38',1,'2025-03-13 08:52:38',1,'Active'),(11,'John','Doe','johndoe3','$2y$10$o2l8mzX9Wiwkg3SbLT5bGOP1Ze5q9Mr1lTIKL55FozdYv4J12twXe','Employee','2025-03-13 08:56:00',1,'2025-03-13 08:56:00',1,'Active');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER employees_audit_insert

AFTER INSERT ON employees

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, NewValue, AdminID, Timestamp)

    VALUES (

        'Employees', 

        NEW.EmployeeID, 

        'Added', 

        CONCAT('Employee: ', NEW.Username),

        NEW.Created_by, 

        NOW()

    );

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER employees_audit_update

AFTER UPDATE ON employees

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, NewValue, AdminID, Timestamp)

    VALUES (

        'Employees', 

        NEW.EmployeeID, 

        'Updated', 

        CONCAT('Employee: ', NEW.Username),

        NEW.Updated_by, 

        NOW()

    );

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary view structure for view `orderdetailsview`
--

DROP TABLE IF EXISTS `orderdetailsview`;
/*!50001 DROP VIEW IF EXISTS `orderdetailsview`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `orderdetailsview` AS SELECT 
 1 AS `OrderID`,
 1 AS `ProductName`,
 1 AS `Quantity`,
 1 AS `Price`,
 1 AS `Total`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `orderline`
--

DROP TABLE IF EXISTS `orderline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orderline` (
  `OrderLineID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`OrderLineID`),
  KEY `ProductID` (`ProductID`),
  KEY `OrderID` (`OrderID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderline`
--

LOCK TABLES `orderline` WRITE;
/*!40000 ALTER TABLE `orderline` DISABLE KEYS */;
INSERT INTO `orderline` VALUES (2,14,2,1,55.00),(3,12,2,1,21.00),(4,12,3,4,21.00),(5,11,4,5,21.00),(6,15,4,2,25.00),(7,16,4,3,20.00),(8,11,5,5,21.00),(9,15,5,2,25.00),(10,16,5,3,20.00),(11,16,5,10,20.00),(12,14,6,1,55.00),(13,14,7,1,55.00),(14,15,7,1,25.00),(15,19,7,1,12.00),(16,14,9,1,55.00),(17,13,10,5,21.00),(18,14,11,10,55.00),(19,16,12,5,20.00),(20,14,12,5,55.00),(21,14,13,4,55.00);
/*!40000 ALTER TABLE `orderline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) DEFAULT NULL,
  `Date` datetime NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `AmountReceived` decimal(10,2) NOT NULL DEFAULT 0.00,
  `CustomerChange` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Status` char(5) NOT NULL,
  `Delivery` int(11) DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `CustomerID` (`CustomerID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,2,'2025-03-07 21:44:24',55.00,0.00,0.00,'Paid',0,'2025-03-07 21:44:24',1,'2025-03-07 21:44:24',1),(2,2,'2025-03-07 21:50:26',76.00,0.00,0.00,'Paid',0,'2025-03-07 21:50:26',1,'2025-03-07 21:50:26',1),(3,2,'2025-03-07 21:57:27',84.00,0.00,0.00,'Paid',0,'2025-03-07 21:57:27',1,'2025-03-07 21:57:27',1),(4,10,'2025-03-08 17:17:47',215.00,0.00,0.00,'Paid',0,'2025-03-08 17:17:47',1,'2025-03-08 17:17:47',1),(5,2,'2025-03-08 17:19:13',415.00,0.00,0.00,'Paid',0,'2025-03-08 17:19:13',1,'2025-03-08 17:19:13',1),(6,2,'2025-03-08 21:22:44',55.00,0.00,0.00,'Paid',0,'2025-03-08 21:22:44',1,'2025-03-08 21:22:44',1),(7,2,'2025-03-09 12:07:13',92.00,0.00,0.00,'Paid',0,'2025-03-09 12:07:13',1,'2025-03-09 12:07:13',1),(9,2,'2025-03-09 16:14:54',55.00,0.00,0.00,'Paid',0,'2025-03-09 16:14:54',1,'2025-03-09 16:14:54',1),(10,2,'2025-03-09 16:52:04',105.00,0.00,0.00,'Paid',0,'2025-03-09 16:52:04',1,'2025-03-09 16:52:04',1),(11,2,'2025-03-10 09:53:22',550.00,0.00,0.00,'Paid',0,'2025-03-10 09:53:22',1,'2025-03-10 09:53:22',1),(12,2,'2025-03-11 08:24:38',375.00,0.00,0.00,'Paid',0,'2025-03-11 08:24:38',1,'2025-03-11 08:24:38',1),(13,2,'2025-03-11 08:26:14',220.00,0.00,0.00,'Paid',0,'2025-03-11 08:26:14',1,'2025-03-11 08:26:14',1);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER orders_audit_insert

AFTER INSERT ON Orders

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, NewValue, AdminID, Timestamp)

    VALUES ('Orders', NEW.OrderID, 'Added', 'OrderDate', NEW.Date, NEW.Created_by, NOW());

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER orders_audit_update

AFTER UPDATE ON Orders

FOR EACH ROW

BEGIN

    -- Check and log only if Total changes

    IF OLD.Total <> NEW.Total THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('Orders', NEW.OrderID, 'Updated', 'Total', OLD.Total, NEW.Total, NEW.Updated_by, NOW());

    END IF;



    -- Check and log only if Status changes

    IF OLD.Status <> NEW.Status THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('Orders', NEW.OrderID, 'Updated', 'Status', OLD.Status, NEW.Status, NEW.Updated_by, NOW());

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `StockQuantity` int(11) NOT NULL,
  `Status` enum('In Stock','Out of Stock') NOT NULL DEFAULT 'In Stock',
  `SupplierID` int(11) NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  `UnitID` int(11) NOT NULL,
  PRIMARY KEY (`ProductID`),
  KEY `CategoryID` (`CategoryID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`),
  KEY `fk_products_units` (`UnitID`),
  CONSTRAINT `fk_products_units` FOREIGN KEY (`UnitID`) REFERENCES `units` (`UnitID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (10,'Mountain Dew',8,23.00,330,'In Stock',2,'2025-03-06 14:44:16',1,'2025-03-12 14:54:24',1,1),(11,'Coca-cola',8,21.00,219,'In Stock',2,'2025-03-06 14:44:24',1,'2025-03-12 14:54:24',1,1),(12,'Sprite',8,21.00,225,'In Stock',2,'2025-03-06 14:44:34',1,'2025-03-12 14:54:24',1,1),(13,'C2 (Red)',8,21.00,245,'In Stock',2,'2025-03-06 14:44:59',1,'2025-03-12 14:54:24',1,1),(14,'Chicken Nuggets',11,55.00,33,'In Stock',3,'2025-03-06 21:34:37',1,'2025-03-13 14:53:53',0,1),(15,'Intermediate Paper',15,25.00,65,'In Stock',3,'2025-03-06 21:37:02',1,'2025-03-11 13:37:15',0,1),(16,'Argentina Beef Loaf',10,20.00,16,'In Stock',3,'2025-03-06 21:39:20',1,'2025-03-12 21:51:27',1,1),(17,'Colgate',14,3.00,15,'In Stock',3,'2025-03-08 09:18:58',1,'2025-03-11 13:37:15',0,1),(18,'Kagayaku Soap',14,0.00,0,'Out of Stock',0,'2025-03-08 16:50:45',1,'2025-03-11 13:37:15',0,1),(19,'Nova',10,12.00,45,'In Stock',3,'2025-03-09 11:58:00',1,'2025-03-11 13:37:15',0,1),(20,'Silver Swan Soy Sauce',12,0.00,0,'Out of Stock',0,'2025-03-10 11:12:22',1,'2025-03-11 13:37:15',0,1),(21,'Silver Swan Vinegar',12,0.00,0,'Out of Stock',0,'2025-03-10 11:12:33',1,'2025-03-11 13:37:15',0,1),(22,'Pic-a',10,0.00,0,'Out of Stock',0,'2025-03-10 11:13:08',1,'2025-03-11 13:37:15',0,1),(23,'Kohaku (Red)',10,50.00,35,'In Stock',2,'2025-03-11 13:49:23',1,'2025-03-12 15:12:06',1,2),(24,'Kohaku (Yellow)',10,50.00,10,'In Stock',2,'2025-03-12 14:58:05',1,'2025-03-12 15:12:06',0,2);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receiving`
--

DROP TABLE IF EXISTS `receiving`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receiving` (
  `ReceivingID` int(11) NOT NULL AUTO_INCREMENT,
  `SupplierID` int(11) DEFAULT NULL,
  `Date` datetime NOT NULL,
  `Status` varchar(20) DEFAULT 'Pending',
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  PRIMARY KEY (`ReceivingID`),
  KEY `SupplierID` (`SupplierID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receiving`
--

LOCK TABLES `receiving` WRITE;
/*!40000 ALTER TABLE `receiving` DISABLE KEYS */;
INSERT INTO `receiving` VALUES (8,2,'2025-03-06 00:00:00','Received','2025-03-06 07:45:53',1,'2025-03-06 15:20:04',1),(9,3,'2025-03-06 00:00:00','Received','2025-03-06 14:41:22',1,'0000-00-00 00:00:00',1),(10,2,'2025-03-06 00:00:00','Received','2025-03-06 14:42:30',1,'2025-03-06 21:42:42',1),(11,3,'2025-03-06 00:00:00','Received','2025-03-06 14:48:29',1,'2025-03-06 21:48:33',1),(12,3,'2025-03-09 00:00:00','Received','2025-03-09 05:00:47',1,'0000-00-00 00:00:00',1),(13,3,'2025-03-09 00:00:00','Received','2025-03-09 09:24:09',1,'2025-03-09 16:24:43',1),(14,4,'2025-03-11 00:00:00','Received','2025-03-11 08:24:57',1,'0000-00-00 00:00:00',1),(15,2,'2025-03-12 00:00:00','Received','2025-03-12 07:59:40',1,'2025-03-12 15:12:06',1),(16,2,'2025-03-13 00:00:00','Pending','2025-03-13 00:33:41',1,'0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `receiving` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER receiving_audit_insert

AFTER INSERT ON Receiving

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, NewValue, AdminID, Timestamp)

    VALUES ('Receiving', NEW.ReceivingID, 'Added', 'Date', NEW.Date, NEW.Created_by, NOW());

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER receiving_audit_update

AFTER UPDATE ON Receiving

FOR EACH ROW

BEGIN

    -- Log only if SupplierID changes

    IF OLD.SupplierID <> NEW.SupplierID THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('Receiving', NEW.ReceivingID, 'Updated', 'SupplierID', OLD.SupplierID, NEW.SupplierID, NEW.Updated_by, NOW());

    END IF;



    -- Log only if Date changes

    IF OLD.Date <> NEW.Date THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('Receiving', NEW.ReceivingID, 'Updated', 'Date', OLD.Date, NEW.Date, NEW.Updated_by, NOW());

    END IF;



    -- Log only if Status changes

    IF OLD.Status <> NEW.Status THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('Receiving', NEW.ReceivingID, 'Updated', 'Status', OLD.Status, NEW.Status, NEW.Updated_by, NOW());

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `receiving_details`
--

DROP TABLE IF EXISTS `receiving_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receiving_details` (
  `ReceivingDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `ReceivingID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitCost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ReceivingDetailID`),
  KEY `ReceivingID` (`ReceivingID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receiving_details`
--

LOCK TABLES `receiving_details` WRITE;
/*!40000 ALTER TABLE `receiving_details` DISABLE KEYS */;
INSERT INTO `receiving_details` VALUES (5,8,10,115,23.00),(6,8,11,115,21.00),(7,8,12,115,21.00),(8,8,13,115,21.00),(9,9,16,50,20.00),(10,9,14,60,55.00),(11,10,15,30,25.00),(12,11,15,50,25.00),(13,12,19,50,12.00),(14,13,17,20,3.00),(15,14,23,25,35.00),(19,15,24,10,35.00),(20,15,23,10,35.00),(21,16,24,50,1500.00);
/*!40000 ALTER TABLE `receiving_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `receiving_details_view`
--

DROP TABLE IF EXISTS `receiving_details_view`;
/*!50001 DROP VIEW IF EXISTS `receiving_details_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `receiving_details_view` AS SELECT 
 1 AS `ReceivingID`,
 1 AS `SupplierID`,
 1 AS `Date`,
 1 AS `ReceivingDetailID`,
 1 AS `product_name`,
 1 AS `Quantity`,
 1 AS `UnitCost`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `return_details`
--

DROP TABLE IF EXISTS `return_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `return_details` (
  `ReturnDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `ReturnID` int(11) DEFAULT NULL,
  `QuantityReturned` int(11) NOT NULL,
  `Reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ReturnDetailID`),
  KEY `ProductID` (`ProductID`),
  KEY `ReturnID` (`ReturnID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_details`
--

LOCK TABLES `return_details` WRITE;
/*!40000 ALTER TABLE `return_details` DISABLE KEYS */;
INSERT INTO `return_details` VALUES (1,14,9,1,'Wrong Item'),(2,16,9,1,'Wrong Item'),(3,14,10,1,'Wrong Item'),(4,14,11,1,'Wrong Item');
/*!40000 ALTER TABLE `return_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `returns`
--

DROP TABLE IF EXISTS `returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `returns` (
  `ReturnID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ReturnDate` datetime NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  PRIMARY KEY (`ReturnID`),
  KEY `CustomerID` (`CustomerID`),
  KEY `OrderID` (`OrderID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returns`
--

LOCK TABLES `returns` WRITE;
/*!40000 ALTER TABLE `returns` DISABLE KEYS */;
INSERT INTO `returns` VALUES (1,1,1,'2025-03-05 00:00:00','2025-03-05 15:22:35',1,'2025-03-09 16:28:39',2),(2,9,4,'2025-03-08 00:00:00','2025-03-08 09:33:34',1,'2025-03-08 09:33:34',1),(3,9,15,'2025-03-09 00:00:00','2025-03-09 16:27:36',1,'2025-03-09 16:27:36',1),(7,1,123,'2023-10-01 00:00:00','2025-03-12 21:46:28',1,'2025-03-12 21:46:28',0),(9,2,12,'2025-03-12 00:00:00','2025-03-12 21:51:27',1,'2025-03-12 21:51:27',0),(10,2,13,'2025-03-12 00:00:00','2025-03-12 22:08:11',1,'2025-03-12 22:08:11',0),(11,2,12,'2025-03-13 00:00:00','2025-03-13 14:53:53',1,'2025-03-13 14:53:53',0);
/*!40000 ALTER TABLE `returns` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER return_audit_insert

AFTER INSERT ON returns

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, NewValue, AdminID, Timestamp)

    VALUES ('Returns', NEW.ReturnID, 'Added', 'ReturnDate', NEW.ReturnDate, NEW.Created_by, NOW());

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER return_audit_update

AFTER UPDATE ON returns

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

    VALUES ('Returns', NEW.ReturnID, 'Updated', 'ReturnDate', OLD.ReturnDate, NEW.ReturnDate, NEW.Updated_by, NOW());

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `returntosupplier`
--

DROP TABLE IF EXISTS `returntosupplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `returntosupplier` (
  `ReturnSupplierID` int(11) NOT NULL AUTO_INCREMENT,
  `SupplierID` int(11) DEFAULT NULL,
  `ReturnDate` datetime NOT NULL,
  `Status` int(11) NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  PRIMARY KEY (`ReturnSupplierID`),
  KEY `SupplierID` (`SupplierID`),
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returntosupplier`
--

LOCK TABLES `returntosupplier` WRITE;
/*!40000 ALTER TABLE `returntosupplier` DISABLE KEYS */;
INSERT INTO `returntosupplier` VALUES (1,2,'2025-03-08 09:13:11',0,'2025-03-08 09:13:11',1,'2025-03-08 09:13:11',0),(2,2,'2025-03-08 09:14:03',0,'2025-03-08 09:14:03',1,'2025-03-08 09:14:03',0),(3,3,'2025-03-09 16:30:38',0,'2025-03-09 16:30:38',1,'2025-03-09 16:30:38',0),(6,2,'2025-03-12 00:00:00',0,'2025-03-12 14:45:25',1,'2025-03-12 14:45:25',0),(7,2,'2025-03-12 00:00:00',0,'2025-03-12 14:51:10',1,'2025-03-12 14:51:10',0),(8,2,'2025-03-12 00:00:00',0,'2025-03-12 14:54:24',1,'2025-03-12 14:54:24',0);
/*!40000 ALTER TABLE `returntosupplier` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER returntosupplier_audit_insert

AFTER INSERT ON ReturnToSupplier

FOR EACH ROW

BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, NewValue, AdminID, Timestamp)

    VALUES ('ReturnToSupplier', NEW.ReturnSupplierID, 'Added', 'ReturnDate', NEW.ReturnDate, NEW.Created_by, NOW());

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER returntosupplier_audit_update

AFTER UPDATE ON ReturnToSupplier

FOR EACH ROW

BEGIN

    -- Log only if SupplierID changes

    IF OLD.SupplierID <> NEW.SupplierID THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('ReturnToSupplier', NEW.ReturnSupplierID, 'Updated', 'SupplierID', OLD.SupplierID, NEW.SupplierID, NEW.Updated_by, NOW());

    END IF;



    -- Log only if ReturnDate changes

    IF OLD.ReturnDate <> NEW.ReturnDate THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('ReturnToSupplier', NEW.ReturnSupplierID, 'Updated', 'ReturnDate', OLD.ReturnDate, NEW.ReturnDate, NEW.Updated_by, NOW());

    END IF;



    -- Log only if Reason changes

    IF OLD.Reason <> NEW.Reason THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('ReturnToSupplier', NEW.ReturnSupplierID, 'Updated', 'Reason', OLD.Reason, NEW.Reason, NEW.Updated_by, NOW());

    END IF;



    -- Log only if Status changes

    IF OLD.Status <> NEW.Status THEN

        INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID, Timestamp)

        VALUES ('ReturnToSupplier', NEW.ReturnSupplierID, 'Updated', 'Status', OLD.Status, NEW.Status, NEW.Updated_by, NOW());

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `returntosupplierdetails`
--

DROP TABLE IF EXISTS `returntosupplierdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `returntosupplierdetails` (
  `ReturnSupplierDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `ReturnSupplierID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `QuantityReturned` int(11) NOT NULL,
  `Reason` varchar(255) NOT NULL,
  PRIMARY KEY (`ReturnSupplierDetailID`),
  KEY `ReturnSupplierID` (`ReturnSupplierID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returntosupplierdetails`
--

LOCK TABLES `returntosupplierdetails` WRITE;
/*!40000 ALTER TABLE `returntosupplierdetails` DISABLE KEYS */;
INSERT INTO `returntosupplierdetails` VALUES (1,1,10,5,''),(2,2,10,5,''),(3,3,17,5,''),(4,6,10,5,''),(5,7,10,5,'Damaged'),(6,7,11,6,'Damaged'),(7,8,10,5,'Damaged'),(8,8,11,5,'Damaged'),(9,8,12,5,'Wrong Item'),(10,8,13,5,'Overstock');
/*!40000 ALTER TABLE `returntosupplierdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `supplier_order_view`
--

DROP TABLE IF EXISTS `supplier_order_view`;
/*!50001 DROP VIEW IF EXISTS `supplier_order_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `supplier_order_view` AS SELECT 
 1 AS `ReceivingID`,
 1 AS `order_date`,
 1 AS `supplier_name`,
 1 AS `order_status`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `SupplierID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `ProfileImage` varchar(255) DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Created_By` int(11) NOT NULL,
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Updated_By` int(11) NOT NULL,
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`SupplierID`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `PhoneNumber` (`PhoneNumber`),
  UNIQUE KEY `Address` (`Address`) USING HASH,
  KEY `Created_By` (`Created_By`),
  KEY `Updated_By` (`Updated_By`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (2,'Supplier2','supplier2, address','0912345689',NULL,'2025-03-03 14:29:33',1,'2025-03-11 15:28:55',1,'Active'),(3,'Supplier3','Mintal, Davao City','09123456789',NULL,'2025-03-06 20:10:31',1,'2025-03-06 20:10:31',1,'Active'),(4,'Supplier4','Calinan, Davao City','09123456788',NULL,'2025-03-11 12:16:05',1,'2025-03-11 12:16:05',1,'Active');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `UnitID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Abbreviation` varchar(10) NOT NULL,
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`UnitID`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `Abbreviation` (`Abbreviation`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'Piece','pc','Active','2025-03-11 05:36:00','2025-03-11 05:36:00'),(2,'Kilogram','kg','Active','2025-03-11 05:36:00','2025-03-11 05:36:00'),(3,'Liter','L','Active','2025-03-11 05:36:00','2025-03-11 05:36:00'),(4,'Pack','pk','Active','2025-03-11 05:36:00','2025-03-11 05:36:00'),(5,'Box','bx','Active','2025-03-11 05:36:00','2025-03-11 05:36:00');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'smartstock_inventory'
--
/*!50003 DROP PROCEDURE IF EXISTS `AddProduct` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `AddProduct`(

    IN p_Name VARCHAR(255),

    IN p_CategoryID INT,

    IN p_UnitID INT,

    IN p_CreatedBy INT

)
BEGIN

    INSERT INTO products (Name, CategoryID, UnitID, StockQuantity, Price, Status, Created_At, Created_By)

    VALUES (p_Name, p_CategoryID, p_UnitID, 0, 0.00, 'Out of Stock', NOW(), p_CreatedBy);

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `DiscardStock` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `DiscardStock`(

    IN p_AdminID INT,

    IN p_ProductID INT,

    IN p_Reason VARCHAR(255),

    IN p_Quantity INT

)
BEGIN

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

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetDashboardStats` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `GetDashboardStats`()
BEGIN

    -- Total Sales (Completed Orders)

    SELECT COALESCE(SUM(total), 0) AS total_sales FROM orders WHERE Status = 'Paid';



    -- Total Orders

    SELECT COUNT(*) AS total_orders FROM orders;



    -- Total Products in Stock

    SELECT COALESCE(SUM(StockQuantity), 0) AS total_products FROM products;



    -- Low Stock Products (Stock < 5)

    SELECT COUNT(*) AS low_stock_products FROM products WHERE StockQuantity < 5;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProducts` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `GetProducts`(IN filterType VARCHAR(20))
BEGIN

    SELECT p.Name, p.ProductID, p.Price, s.Name AS SupplierName, 

           c.Name AS CategoryName, p.StockQuantity, p.Status 

    FROM products p

    LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID

    LEFT JOIN categories c ON p.CategoryID = c.CategoryID

    WHERE (filterType = 'All') OR 

          (filterType = 'In Stock' AND p.Status = 'In Stock') OR 

          (filterType = 'Out of Stock' AND p.Status = 'Out of Stock')

    ORDER BY p.Name ASC;

    

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProductsWithPage` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `GetProductsWithPage`(IN `filterStatus` VARCHAR(20), IN `limitCount` INT, IN `offsetCount` INT)
BEGIN

    SELECT 

        p.ProductID, 

        p.Name, 

        p.Price, 

        s.Name AS SupplierName, 

        c.Name AS CategoryName, 

        p.StockQuantity AS Stock, 

        p.Status

    FROM Products p

    LEFT JOIN Suppliers s ON p.SupplierID = s.SupplierID

    LEFT JOIN Categories c ON p.CategoryID = c.CategoryID

    WHERE (filterStatus IS NULL OR p.Status = filterStatus)

    ORDER BY p.Name ASC

    LIMIT limitCount OFFSET offsetCount;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProductsWithPageAndSearch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `GetProductsWithPageAndSearch`(IN `filterStatus` VARCHAR(20), IN `searchQuery` VARCHAR(255), IN `limitCount` INT, IN `offsetCount` INT)
SELECT 

    p.ProductID, 

    p.Name, 

    p.Price, 

    p.UnitID,

    s.Name AS SupplierName, 

    c.Name AS CategoryName, 

    p.StockQuantity, 

    u.Abbreviation,  

    p.Status

FROM Products p

LEFT JOIN Suppliers s ON p.SupplierID = s.SupplierID

LEFT JOIN Categories c ON p.CategoryID = c.CategoryID

LEFT JOIN Units u ON p.UnitID = u.UnitID  -- Join the Units table

WHERE (filterStatus = 'All' OR p.Status = filterStatus)

AND (searchQuery IS NULL OR p.Name LIKE CONCAT('%', searchQuery, '%'))

ORDER BY p.Name ASC

LIMIT limitCount OFFSET offsetCount ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetSalesByCategory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `GetSalesByCategory`()
BEGIN

    SELECT c.Name AS CategoryName, SUM(o.Total) AS TotalSales

    FROM orders o

    JOIN orderline ol ON o.OrderID = ol.OrderID

    JOIN products p ON ol.ProductID = p.ProductID

    JOIN categories c ON p.CategoryID = c.CategoryID

    GROUP BY c.Name

    ORDER BY TotalSales DESC;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_audit_log` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE PROCEDURE `insert_audit_log`(IN `table_name` VARCHAR(50), IN `record_id` INT, IN `action_type` ENUM('Add','Change'), IN `column_name` VARCHAR(50), IN `old_value` TEXT, IN `new_value` TEXT, IN `admin_id` INT)
BEGIN

    INSERT INTO audit_logs (TableName, RecordID, ActionType, ColumnName, OldValue, NewValue, AdminID)

    VALUES (table_name, record_id, action_type, column_name, old_value, new_value, admin_id);

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `orderdetailsview`
--

/*!50001 DROP VIEW IF EXISTS `orderdetailsview`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `orderdetailsview` AS select `ol`.`OrderID` AS `OrderID`,`p`.`Name` AS `ProductName`,`ol`.`Quantity` AS `Quantity`,`ol`.`Price` AS `Price`,`ol`.`Quantity` * `ol`.`Price` AS `Total` from (`orderline` `ol` join `products` `p` on(`ol`.`ProductID` = `p`.`ProductID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `receiving_details_view`
--

/*!50001 DROP VIEW IF EXISTS `receiving_details_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `receiving_details_view` AS select `r`.`ReceivingID` AS `ReceivingID`,`r`.`SupplierID` AS `SupplierID`,`r`.`Date` AS `Date`,`rd`.`ReceivingDetailID` AS `ReceivingDetailID`,`p`.`Name` AS `product_name`,`rd`.`Quantity` AS `Quantity`,`rd`.`UnitCost` AS `UnitCost` from ((`receiving` `r` left join `receiving_details` `rd` on(`r`.`ReceivingID` = `rd`.`ReceivingID`)) left join `products` `p` on(`rd`.`ProductID` = `p`.`ProductID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `supplier_order_view`
--

/*!50001 DROP VIEW IF EXISTS `supplier_order_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `supplier_order_view` AS select `r`.`ReceivingID` AS `ReceivingID`,`r`.`Date` AS `order_date`,`s`.`Name` AS `supplier_name`,`r`.`Status` AS `order_status` from (`receiving` `r` join `suppliers` `s` on(`r`.`SupplierID` = `s`.`SupplierID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-13 15:00:54
