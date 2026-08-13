-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: db_laundry
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Budi Santoso','081234567890','Jl. Dharmawangsa No. 12, Surabaya','2026-08-12 16:32:02','2026-08-13 08:49:09','2026-08-13 08:49:09'),(2,'Siti Rahmawati','085712345678','Jl. Mulyorejo Indah No. 45, Surabaya','2026-08-12 16:32:02','2026-08-12 16:32:02',NULL),(3,'Ahmad Fauzi','087890123456','Jl. Gubeng Kertajaya No. 8, Surabaya','2026-08-12 16:32:02','2026-08-12 16:32:02',NULL),(4,'Pelanggan Uji Coba','089923902923','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:34:49','2026-08-12 16:34:49','2026-08-12 16:34:49'),(5,'Pelanggan Uji Coba','089997390497','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:36:00','2026-08-12 16:36:00','2026-08-12 16:36:00'),(6,'Pelanggan Uji Coba','089973475087','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:37:15','2026-08-12 16:37:15','2026-08-12 16:37:15'),(7,'Pelanggan Uji Coba','089927670214','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:42:08','2026-08-12 16:42:08','2026-08-12 16:42:08'),(8,'Pelanggan Uji Coba','089999800035','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:43:25','2026-08-12 16:43:25','2026-08-12 16:43:25'),(9,'Pelanggan Uji Coba','089940663932','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:43:48','2026-08-12 16:43:48','2026-08-12 16:43:48'),(10,'Pelanggan Uji Coba','089927818044','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:44:25','2026-08-12 16:44:25','2026-08-12 16:44:25'),(11,'Pelanggan Uji Coba','089954246644','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:53:37','2026-08-12 16:53:37','2026-08-12 16:53:37'),(12,'Pelanggan Uji Coba','089962875252','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:54:00','2026-08-12 16:54:00','2026-08-12 16:54:00'),(13,'Pelanggan Uji Coba','089937806705','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:54:52','2026-08-12 16:54:52','2026-08-12 16:54:52'),(14,'Pelanggan Uji Coba','089912866986','Jl. Uji Coba No. 99, Surabaya','2026-08-12 16:58:35','2026-08-12 16:58:35','2026-08-12 16:58:35'),(15,'Balqis','12345678','','2026-08-13 09:03:50','2026-08-13 09:03:56','2026-08-13 09:03:56');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-08-12-000001','App\\Database\\Migrations\\CreateUsersTable','default','App',1786552257,1),(2,'2026-08-12-000002','App\\Database\\Migrations\\CreateCustomersTable','default','App',1786552257,1),(3,'2026-08-12-000003','App\\Database\\Migrations\\CreateServicesTable','default','App',1786552257,1),(4,'2026-08-12-000004','App\\Database\\Migrations\\CreateTransactionsTable','default','App',1786552257,1),(5,'2026-08-12-000005','App\\Database\\Migrations\\CreateTransactionItemsTable','default','App',1786552258,1),(6,'2026-08-12-000006','App\\Database\\Migrations\\CreateStatusHistoriesTable','default','App',1786552258,1),(7,'2026-08-12-000007','App\\Database\\Migrations\\CreatePaymentsTable','default','App',1786552258,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `method` enum('tunai','transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tunai',
  `paid_at` datetime NOT NULL,
  `received_by` int unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_transaction_id_foreign` (`transaction_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,20000.00,'tunai','2026-08-12 16:34:49',1,'2026-08-12 16:34:49','2026-08-12 16:34:49'),(2,1,33000.00,'tunai','2026-08-12 16:34:49',1,'2026-08-12 16:34:49','2026-08-12 16:34:49'),(3,2,20000.00,'tunai','2026-08-12 16:36:00',1,'2026-08-12 16:36:00','2026-08-12 16:36:00'),(4,2,33000.00,'tunai','2026-08-12 16:36:00',1,'2026-08-12 16:36:00','2026-08-12 16:36:00'),(5,3,20000.00,'tunai','2026-08-12 16:37:15',1,'2026-08-12 16:37:15','2026-08-12 16:37:15'),(6,3,33000.00,'tunai','2026-08-12 16:37:15',1,'2026-08-12 16:37:15','2026-08-12 16:37:15'),(7,4,20000.00,'tunai','2026-08-12 16:42:08',1,'2026-08-12 16:42:08','2026-08-12 16:42:08'),(8,4,33000.00,'tunai','2026-08-12 16:42:08',1,'2026-08-12 16:42:08','2026-08-12 16:42:08'),(9,5,20000.00,'tunai','2026-08-12 16:43:25',1,'2026-08-12 16:43:25','2026-08-12 16:43:25'),(10,5,33000.00,'tunai','2026-08-12 16:43:25',1,'2026-08-12 16:43:25','2026-08-12 16:43:25'),(11,6,20000.00,'tunai','2026-08-12 16:43:48',1,'2026-08-12 16:43:48','2026-08-12 16:43:48'),(12,6,33000.00,'tunai','2026-08-12 16:43:49',1,'2026-08-12 16:43:49','2026-08-12 16:43:49'),(13,7,20000.00,'tunai','2026-08-12 16:44:25',1,'2026-08-12 16:44:25','2026-08-12 16:44:25'),(14,7,33000.00,'tunai','2026-08-12 16:44:25',1,'2026-08-12 16:44:25','2026-08-12 16:44:25'),(15,8,20000.00,'tunai','2026-08-12 16:53:37',1,'2026-08-12 16:53:37','2026-08-12 16:53:37'),(16,8,33000.00,'tunai','2026-08-12 16:53:37',1,'2026-08-12 16:53:37','2026-08-12 16:53:37'),(17,9,20000.00,'tunai','2026-08-12 16:54:00',1,'2026-08-12 16:54:00','2026-08-12 16:54:00'),(18,9,33000.00,'tunai','2026-08-12 16:54:00',1,'2026-08-12 16:54:00','2026-08-12 16:54:00'),(19,10,20000.00,'tunai','2026-08-12 16:54:52',1,'2026-08-12 16:54:52','2026-08-12 16:54:52'),(20,10,33000.00,'tunai','2026-08-12 16:54:52',1,'2026-08-12 16:54:52','2026-08-12 16:54:52'),(21,11,20000.00,'tunai','2026-08-12 16:58:35',1,'2026-08-12 16:58:35','2026-08-12 16:58:35'),(22,11,33000.00,'tunai','2026-08-12 16:58:35',1,'2026-08-12 16:58:35','2026-08-12 16:58:35'),(23,12,30000.00,'tunai','2026-08-12 17:04:14',1,'2026-08-12 17:04:14','2026-08-12 17:04:14'),(24,14,50000.00,'tunai','2026-08-13 09:03:28',1,'2026-08-13 09:03:28','2026-08-13 09:03:28');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('kiloan','satuan','ekspres') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kiloan',
  `unit` enum('kg','pcs') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `duration_estimate_hours` int NOT NULL DEFAULT '24',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Cuci Kiloan Reguler (2 Hari)','kiloan','kg',7000.00,48,1,'2026-08-12 16:32:02','2026-08-12 16:32:02'),(2,'Cuci Kiloan Kilat (1 Hari)','kiloan','kg',10000.00,24,1,'2026-08-12 16:32:02','2026-08-12 16:32:02'),(3,'Cuci Kiloan Ekspres (6 Jam)','ekspres','kg',15000.00,6,1,'2026-08-12 16:32:02','2026-08-12 16:32:02'),(4,'Cuci Satuan Bedcover Besar','satuan','pcs',25000.00,48,1,'2026-08-12 16:32:02','2026-08-12 16:32:02'),(5,'Cuci Satuan Jas / Blazer','satuan','pcs',20000.00,48,1,'2026-08-12 16:32:02','2026-08-12 16:32:02'),(6,'Setrika Saja (Kiloan)','kiloan','kg',5000.00,24,1,'2026-08-12 16:32:02','2026-08-12 16:32:02');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_histories`
--

DROP TABLE IF EXISTS `status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_histories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int unsigned NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` int unsigned NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_histories_transaction_id_foreign` (`transaction_id`),
  KEY `status_histories_changed_by_foreign` (`changed_by`),
  CONSTRAINT `status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `status_histories_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_histories`
--

LOCK TABLES `status_histories` WRITE;
/*!40000 ALTER TABLE `status_histories` DISABLE KEYS */;
INSERT INTO `status_histories` VALUES (1,1,'diterima',1,'Pesanan diterima','2026-08-12 16:34:49'),(2,2,'diterima',1,'Pesanan diterima','2026-08-12 16:36:00'),(3,3,'diterima',1,'Pesanan diterima','2026-08-12 16:37:15'),(4,4,'diterima',1,'Pesanan diterima','2026-08-12 16:42:08'),(5,5,'diterima',1,'Pesanan diterima','2026-08-12 16:43:25'),(6,6,'diterima',1,'Pesanan diterima','2026-08-12 16:43:48'),(7,7,'diterima',1,'Pesanan diterima','2026-08-12 16:44:25'),(8,8,'diterima',1,'Pesanan diterima','2026-08-12 16:53:37'),(9,9,'diterima',1,'Pesanan diterima','2026-08-12 16:54:00'),(10,10,'diterima',1,'Pesanan diterima','2026-08-12 16:54:52'),(11,11,'diterima',1,'Pesanan diterima','2026-08-12 16:58:35'),(12,12,'diterima',1,'Pesanan cucian baru diterima sistem.','2026-08-12 17:03:17'),(13,12,'dicuci',1,'','2026-08-12 17:04:30'),(14,12,'dikeringkan',1,'','2026-08-13 08:09:12'),(15,12,'disetrika',1,'','2026-08-13 08:10:19'),(16,12,'siap_diambil',1,'','2026-08-13 08:10:38'),(17,13,'diterima',1,'Pesanan cucian baru diterima sistem.','2026-08-13 08:12:09'),(18,13,'dicuci',1,'','2026-08-13 08:13:00'),(19,14,'diterima',1,'Pesanan cucian baru diterima sistem.','2026-08-13 09:02:27'),(20,14,'dicuci',1,'','2026-08-13 09:02:39'),(21,14,'disetrika',1,'','2026-08-13 09:03:18');
/*!40000 ALTER TABLE `status_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_items`
--

DROP TABLE IF EXISTS `transaction_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int unsigned NOT NULL,
  `service_id` int unsigned NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT '1.00',
  `price_per_unit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_items_service_id_foreign` (`service_id`),
  CONSTRAINT `transaction_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_items`
--

LOCK TABLES `transaction_items` WRITE;
/*!40000 ALTER TABLE `transaction_items` DISABLE KEYS */;
INSERT INTO `transaction_items` VALUES (1,1,1,4.00,7000.00,28000.00),(2,1,4,1.00,25000.00,25000.00),(3,2,1,4.00,7000.00,28000.00),(4,2,4,1.00,25000.00,25000.00),(5,3,1,4.00,7000.00,28000.00),(6,3,4,1.00,25000.00,25000.00),(7,4,1,4.00,7000.00,28000.00),(8,4,4,1.00,25000.00,25000.00),(9,5,1,4.00,7000.00,28000.00),(10,5,4,1.00,25000.00,25000.00),(11,6,1,4.00,7000.00,28000.00),(12,6,4,1.00,25000.00,25000.00),(13,7,1,4.00,7000.00,28000.00),(14,7,4,1.00,25000.00,25000.00),(15,8,1,4.00,7000.00,28000.00),(16,8,4,1.00,25000.00,25000.00),(17,9,1,4.00,7000.00,28000.00),(18,9,4,1.00,25000.00,25000.00),(19,10,1,4.00,7000.00,28000.00),(20,10,4,1.00,25000.00,25000.00),(21,11,1,4.00,7000.00,28000.00),(22,11,4,1.00,25000.00,25000.00),(23,12,3,2.00,15000.00,30000.00),(24,13,1,1.00,7000.00,7000.00),(25,14,4,2.00,25000.00,50000.00);
/*!40000 ALTER TABLE `transaction_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('diterima','dicuci','dikeringkan','disetrika','siap_diambil','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diterima',
  `payment_status` enum('belum_bayar','dp','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `estimated_done_at` datetime DEFAULT NULL,
  `picked_up_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_code` (`transaction_code`),
  KEY `transactions_customer_id_foreign` (`customer_id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'LDY-20260812-0001',1,1,53000.00,'selesai','lunas','2026-08-14 16:34:49','2026-08-12 16:34:49','Test transaction','2026-08-12 16:34:49','2026-08-12 16:34:49',NULL),(2,'LDY-20260812-0002',1,1,53000.00,'selesai','lunas','2026-08-14 16:36:00','2026-08-12 16:36:00','Test transaction','2026-08-12 16:36:00','2026-08-12 16:36:00',NULL),(3,'LDY-20260812-0003',1,1,53000.00,'selesai','lunas','2026-08-14 16:37:15','2026-08-12 16:37:15','Test transaction','2026-08-12 16:37:15','2026-08-12 16:37:15',NULL),(4,'LDY-20260812-0004',1,1,53000.00,'selesai','lunas','2026-08-14 16:42:08','2026-08-12 16:42:09','Test transaction','2026-08-12 16:42:08','2026-08-12 16:42:09',NULL),(5,'LDY-20260812-0005',1,1,53000.00,'selesai','lunas','2026-08-14 16:43:25','2026-08-12 16:43:25','Test transaction','2026-08-12 16:43:25','2026-08-12 16:43:25',NULL),(6,'LDY-20260812-0006',1,1,53000.00,'selesai','lunas','2026-08-14 16:43:48','2026-08-12 16:43:49','Test transaction','2026-08-12 16:43:48','2026-08-12 16:43:49',NULL),(7,'LDY-20260812-0007',1,1,53000.00,'selesai','lunas','2026-08-14 16:44:25','2026-08-12 16:44:25','Test transaction','2026-08-12 16:44:25','2026-08-12 16:44:25',NULL),(8,'LDY-20260812-0008',1,1,53000.00,'selesai','lunas','2026-08-14 16:53:37','2026-08-12 16:53:37','Test transaction','2026-08-12 16:53:37','2026-08-12 16:53:37',NULL),(9,'LDY-20260812-0009',1,1,53000.00,'selesai','lunas','2026-08-14 16:54:00','2026-08-12 16:54:00','Test transaction','2026-08-12 16:54:00','2026-08-12 16:54:00',NULL),(10,'LDY-20260812-0010',1,1,53000.00,'selesai','lunas','2026-08-14 16:54:52','2026-08-12 16:54:52','Test transaction','2026-08-12 16:54:52','2026-08-12 16:54:52',NULL),(11,'LDY-20260812-0011',1,1,53000.00,'selesai','lunas','2026-08-14 16:58:35','2026-08-12 16:58:35','Test transaction','2026-08-12 16:58:35','2026-08-12 16:58:35',NULL),(12,'LDY-20260812-0012',2,1,30000.00,'siap_diambil','lunas','2026-08-13 17:03:17',NULL,'','2026-08-12 17:03:17','2026-08-13 08:10:38',NULL),(13,'LDY-20260813-0001',3,1,7000.00,'dicuci','belum_bayar','2026-08-15 08:12:09',NULL,'','2026-08-13 08:12:09','2026-08-13 08:13:00',NULL),(14,'LDY-20260813-0002',3,1,50000.00,'disetrika','lunas','2026-08-15 09:02:27',NULL,'','2026-08-13 09:02:27','2026-08-13 09:03:28',NULL);
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kasir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Laundry','admin@laundry.com','$2y$12$d7fYYVlWBre16eGKu38Ayesm7korcmsc/w1Ezj2fz6G36lYlfdanG','admin','2026-08-12 16:32:02','2026-08-12 16:32:02'),(2,'Kasir Laundry','kasir@laundry.com','$2y$12$sUGMzXO44gTHMwFGU0BcZO56rL5/CmDbHlDi06x/0m0rrjIw4jKOi','kasir','2026-08-12 16:32:02','2026-08-12 16:32:02');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'db_laundry'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-13 21:10:31
