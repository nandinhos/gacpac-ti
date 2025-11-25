mysqldump: [Warning] Using a password on the command line interface can be insecure.
-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: sgaiti_db
-- ------------------------------------------------------
-- Server version	8.0.44

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
-- Table structure for table `asset_photos`
--

DROP TABLE IF EXISTS `asset_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_photos_asset_id_foreign` (`asset_id`),
  CONSTRAINT `asset_photos_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_photos`
--

LOCK TABLES `asset_photos` WRITE;
/*!40000 ALTER TABLE `asset_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subcategory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patrimony_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patrimony_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `purchase_value` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition_rating` int DEFAULT NULL,
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sector_id` bigint unsigned DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custodian_user_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `conta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria_inventario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bmp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `componente` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qtd` int DEFAULT NULL,
  `valor_atualizado` decimal(15,2) DEFAULT NULL,
  `deprec_acumulada` decimal(15,2) DEFAULT NULL,
  `valor_liquido` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assets_qr_code_unique` (`qr_code`),
  KEY `assets_sector_id_foreign` (`sector_id`),
  KEY `assets_custodian_user_id_foreign` (`custodian_user_id`),
  CONSTRAINT `assets_custodian_user_id_foreign` FOREIGN KEY (`custodian_user_id`) REFERENCES `military_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `assets_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets`
--

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;
INSERT INTO `assets` VALUES (1,'SGTI-0001','Desktop Dell Vostro 3888','Computação',NULL,NULL,NULL,'BR-A1B2C3D','FAB-1001',NULL,NULL,NULL,NULL,'2023-02-10','2026-02-10',NULL,NULL,'Em Uso',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(2,'SGTI-0002','Notebook Dell Latitude 7420','Computação',NULL,NULL,NULL,'US-E4F5G6H','FAB-1002',NULL,NULL,NULL,NULL,'2023-05-15','2026-05-15',NULL,NULL,'Em Uso',NULL,NULL,2,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(3,'SGTI-0003','Servidor Dell PowerEdge R750','Computação',NULL,NULL,NULL,'SRV-I7J8K9L','FAB-2001',NULL,NULL,NULL,NULL,'2022-08-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(4,'SGTI-0004','Monitor Gamer LG UltraGear 27\"','Periféricos',NULL,NULL,NULL,'MON-M1N2O3P',NULL,NULL,NULL,NULL,NULL,'2023-11-01',NULL,NULL,NULL,'Em Uso',NULL,NULL,3,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(5,'SGTI-0005','Impressora Multifuncional Brother MFC-L8900CDW','Periféricos',NULL,NULL,NULL,'PRT-Q4R5S6T','FAB-3001',NULL,NULL,NULL,NULL,'2021-09-05',NULL,NULL,NULL,'Em Uso',NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(6,'SGTI-0006','Switch Cisco Catalyst 9200 24p','Comunicações',NULL,NULL,NULL,'SWT-U7V8W9X',NULL,NULL,NULL,NULL,NULL,'2022-10-10',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(7,'SGTI-0007','Desktop HP EliteDesk 800 G9','Computação',NULL,NULL,NULL,'BR-Y1Z2A3B','FAB-1003',NULL,NULL,NULL,NULL,'2023-03-12','2026-03-12',NULL,NULL,'Em Uso',NULL,NULL,6,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(8,'SGTI-0008','Notebook Lenovo ThinkPad T14 Gen 3','Computação',NULL,NULL,NULL,'CN-C4D5E6F','FAB-1004',NULL,NULL,NULL,NULL,'2023-07-20','2026-07-20',NULL,NULL,'Em Uso',NULL,NULL,8,NULL,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(9,'SGTI-0009','Roteador Wireless TP-Link Archer AX50','Comunicações',NULL,NULL,NULL,'RTR-G7H8I9J',NULL,NULL,NULL,NULL,NULL,'2024-01-15',NULL,NULL,NULL,'Em Uso',NULL,NULL,5,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(10,'SGTI-0010','Nobreak APC Smart-UPS 3000VA','Energia',NULL,NULL,NULL,'UPS-K1L2M3N','FAB-4001',NULL,NULL,NULL,NULL,'2020-06-30',NULL,NULL,NULL,'Manutenção',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(11,'SGTI-0011','Tablet Samsung Galaxy Tab S8','Computação',NULL,NULL,NULL,'TAB-O4P5Q6R','FAB-1005',NULL,NULL,NULL,NULL,'2023-09-01','2024-09-01',NULL,NULL,'Em Uso',NULL,NULL,9,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(12,'SGTI-0012','Projetor Epson PowerLite E20','Outros Ativos de TI',NULL,NULL,NULL,'PRJ-S7T8U9V','FAB-5001',NULL,NULL,NULL,NULL,'2022-04-18',NULL,NULL,NULL,'Em Uso',NULL,NULL,7,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(13,'SGTI-0013','Scanner de Mesa Fujitsu ScanSnap iX1600','Periféricos',NULL,NULL,NULL,'SCN-W1X2Y3Z',NULL,NULL,NULL,NULL,NULL,'2023-10-05',NULL,NULL,NULL,'Disponível',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(14,'SGTI-0014','Docking Station Dell WD19S','Periféricos',NULL,NULL,NULL,'DCK-A2B3C4D',NULL,NULL,NULL,NULL,NULL,'2023-05-15',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(15,'SGTI-0015','Webcam Logitech C920 Pro HD','Periféricos',NULL,NULL,NULL,'CAM-E5F6G7H',NULL,NULL,NULL,NULL,NULL,'2023-02-10',NULL,NULL,NULL,'Em Uso',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(16,'SGTI-0016','Teclado Mecânico Redragon Kumara K552','Periféricos',NULL,NULL,NULL,'KBD-I8J9K1L',NULL,NULL,NULL,NULL,NULL,'2024-02-20',NULL,NULL,NULL,'Disponível',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(17,'SGTI-0017','Mouse Logitech MX Master 3S','Periféricos',NULL,NULL,NULL,'MSE-M2N3O4P',NULL,NULL,NULL,NULL,NULL,'2024-02-20',NULL,NULL,NULL,'Disponível',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(18,'SGTI-0018','Servidor de Backup Synology DiskStation DS923+','Computação',NULL,NULL,NULL,'NAS-Q5R6S7T','FAB-2002',NULL,NULL,NULL,NULL,'2019-01-15',NULL,NULL,NULL,'Baixado',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(19,'SGTI-0019','Monitor Dell UltraSharp 24\"','Periféricos',NULL,NULL,NULL,'MON-DELL-U2421E',NULL,NULL,NULL,NULL,NULL,'2023-05-15',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(20,'SGTI-0020','Teclado Dell KB216','Periféricos',NULL,NULL,NULL,'KBD-DELL-KB216',NULL,NULL,NULL,NULL,NULL,'2023-05-15',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(21,'SGTI-0021','Mouse Dell MS116','Periféricos',NULL,NULL,NULL,'MSE-DELL-MS116',NULL,NULL,NULL,NULL,NULL,'2023-05-15',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(22,'SGTI-0022','Desktop Dell Optiplex 7090','Computação',NULL,NULL,NULL,'DT-DELL-OPT7090','FAB-1010',NULL,NULL,NULL,NULL,'2023-01-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(23,'SGTI-0023','Monitor Dell 24\"','Periféricos',NULL,NULL,NULL,'MON-DELL-P2422H-1',NULL,NULL,NULL,NULL,NULL,'2023-01-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(24,'SGTI-0024','Monitor Dell 24\"','Periféricos',NULL,NULL,NULL,'MON-DELL-P2422H-2',NULL,NULL,NULL,NULL,NULL,'2023-01-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(25,'SGTI-0025','Teclado Dell KB216','Periféricos',NULL,NULL,NULL,'KBD-DELL-KB216-2',NULL,NULL,NULL,NULL,NULL,'2023-01-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(26,'SGTI-0026','Mouse Dell MS116','Periféricos',NULL,NULL,NULL,'MSE-DELL-MS116-2',NULL,NULL,NULL,NULL,NULL,'2023-01-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(27,'SGTI-0027','Headset Logitech H390','Periféricos',NULL,NULL,NULL,'HDS-LOGI-H390',NULL,NULL,NULL,NULL,NULL,'2023-01-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(28,'SGTI-0028','Desktop Dell Vostro 3888','Computação',NULL,NULL,NULL,'DT-DELL-VOS3888','FAB-1011',NULL,NULL,NULL,NULL,'2023-06-10',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(29,'SGTI-0029','Monitor LG 22\"','Periféricos',NULL,NULL,NULL,'MON-LG-22MP410',NULL,NULL,NULL,NULL,NULL,'2023-06-10',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(30,'SGTI-0030','Teclado Logitech K120','Periféricos',NULL,NULL,NULL,'KBD-LOGI-K120',NULL,NULL,NULL,NULL,NULL,'2023-06-10',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(31,'SGTI-0031','Mouse Logitech M90','Periféricos',NULL,NULL,NULL,'MSE-LOGI-M90',NULL,NULL,NULL,NULL,NULL,'2023-06-10',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(32,'SGTI-0032','Notebook Dell Latitude 5420','Computação',NULL,NULL,NULL,'NB-DELL-LAT5420','FAB-1012',NULL,NULL,NULL,NULL,'2023-08-01',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(33,'SGTI-0033','Monitor Dell 24\"','Periféricos',NULL,NULL,NULL,'MON-DELL-P2422H-3',NULL,NULL,NULL,NULL,NULL,'2023-08-01',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(34,'SGTI-0034','Docking Station Dell WD19','Periféricos',NULL,NULL,NULL,'DCK-DELL-WD19',NULL,NULL,NULL,NULL,NULL,'2023-08-01',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(35,'SGTI-0035','Teclado Externo Dell (sem fio)','Periféricos',NULL,NULL,NULL,'KBD-DELL-KM5221W',NULL,NULL,NULL,NULL,NULL,'2023-08-01',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(36,'SGTI-0036','Mouse Externo Dell (sem fio)','Periféricos',NULL,NULL,NULL,'MSE-DELL-KM5221W',NULL,NULL,NULL,NULL,NULL,'2023-08-01',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(37,'SGTI-0037','Notebook Lenovo ThinkPad T14','Computação',NULL,NULL,NULL,'NB-LENOVO-T14','FAB-1013',NULL,NULL,NULL,NULL,'2024-02-15',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(38,'SGTI-0038','Mouse sem fio Logitech M185','Periféricos',NULL,NULL,NULL,'MSE-LOGI-M185',NULL,NULL,NULL,NULL,NULL,'2024-02-15',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(39,'SGTI-0039','Projetor Portátil BenQ','Outros Ativos de TI',NULL,NULL,NULL,'PRJ-BENQ-GV30',NULL,NULL,NULL,NULL,NULL,'2023-12-01',NULL,NULL,NULL,'Disponível',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(40,'SGTI-0040','Câmera DSLR Canon T7i com lente 18-55mm','Outros Ativos de TI',NULL,NULL,NULL,'CAM-CANON-T7I',NULL,NULL,NULL,NULL,NULL,'2022-11-11',NULL,NULL,NULL,'Disponível',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(41,'SGTI-0041','Impressora de Rede HP LaserJet Pro M428fdw','Periféricos',NULL,NULL,NULL,'PRT-HP-M428FDW','FAB-3005',NULL,NULL,NULL,NULL,'2022-09-20',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(42,'SGTI-0042','Scanner de Rede Brother ADS-2700W','Periféricos',NULL,NULL,NULL,'SCN-BROTHER-ADS2700W',NULL,NULL,NULL,NULL,NULL,'2023-03-30',NULL,NULL,NULL,'Em Uso',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09');
/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custody_assets`
--

DROP TABLE IF EXISTS `custody_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custody_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `custody_log_id` bigint unsigned NOT NULL,
  `asset_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custody_assets_custody_log_id_foreign` (`custody_log_id`),
  KEY `custody_assets_asset_id_foreign` (`asset_id`),
  CONSTRAINT `custody_assets_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `custody_assets_custody_log_id_foreign` FOREIGN KEY (`custody_log_id`) REFERENCES `custody_logs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custody_assets`
--

LOCK TABLES `custody_assets` WRITE;
/*!40000 ALTER TABLE `custody_assets` DISABLE KEYS */;
INSERT INTO `custody_assets` VALUES (1,1,2,NULL,NULL),(2,1,4,NULL,NULL),(3,1,14,NULL,NULL),(4,2,1,NULL,NULL),(5,2,5,NULL,NULL),(6,2,15,NULL,NULL),(7,3,8,NULL,NULL),(8,3,20,NULL,NULL),(9,4,7,NULL,NULL),(10,4,24,NULL,NULL),(11,4,25,NULL,NULL),(12,4,26,NULL,NULL),(13,5,11,NULL,NULL),(14,5,33,NULL,NULL),(15,5,34,NULL,NULL),(16,6,28,NULL,NULL),(17,6,29,NULL,NULL),(18,6,30,NULL,NULL),(19,6,31,NULL,NULL),(20,7,32,NULL,NULL),(21,7,35,NULL,NULL),(22,7,36,NULL,NULL),(23,8,37,NULL,NULL),(24,8,38,NULL,NULL),(25,9,3,NULL,NULL),(26,9,6,NULL,NULL),(27,9,10,NULL,NULL),(28,10,9,NULL,NULL),(29,10,12,NULL,NULL),(30,11,16,NULL,NULL),(31,11,17,NULL,NULL);
/*!40000 ALTER TABLE `custody_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custody_logs`
--

DROP TABLE IF EXISTS `custody_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custody_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cautela_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `checkout_date` datetime NOT NULL,
  `checkin_date` datetime DEFAULT NULL,
  `term_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_term_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `signed_document_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_document_uploaded_at` timestamp NULL DEFAULT NULL,
  `signed_document_justification` text COLLATE utf8mb4_unicode_ci,
  `signed_document_removed_at` timestamp NULL DEFAULT NULL,
  `signed_document_removal_justification` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custody_logs_cautela_number_unique` (`cautela_number`),
  KEY `custody_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `custody_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `military_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custody_logs`
--

LOCK TABLES `custody_logs` WRITE;
/*!40000 ALTER TABLE `custody_logs` DISABLE KEYS */;
INSERT INTO `custody_logs` VALUES (1,'001/GAC-PAC/2024',2,'2024-01-15 00:00:00','2024-02-15 00:00:00',NULL,NULL,'Empréstimo para projeto de desenvolvimento',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(2,'002/GAC-PAC/2024',3,'2024-02-01 00:00:00',NULL,NULL,NULL,'Equipamentos para trabalho remoto',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(3,'003/GAC-PAC/2024',5,'2024-02-20 00:00:00',NULL,NULL,NULL,'Equipamentos para desenvolvimento de software',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(4,'004/GAC-PAC/2024',9,'2024-03-01 00:00:00','2024-03-15 00:00:00',NULL,NULL,'Equipamentos temporários para manutenção',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(5,'005/GAC-PAC/2024',10,'2024-03-10 00:00:00',NULL,NULL,NULL,'Equipamentos para desenvolvimento mobile',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(6,'006/GAC-PAC/2024',12,'2024-03-20 00:00:00',NULL,NULL,NULL,'Equipamentos para trabalho administrativo',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(7,'007/GAC-PAC/2024',13,'2024-04-01 00:00:00',NULL,NULL,NULL,'Equipamentos para projeto especial',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(8,'008/GAC-PAC/2024',14,'2024-04-10 00:00:00',NULL,NULL,NULL,'Equipamentos para estágio',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(9,'009/GAC-PAC/2023',1,'2023-12-01 00:00:00','2023-12-20 00:00:00',NULL,NULL,'Equipamentos para sala de servidores',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(10,'010/GAC-PAC/2023',4,'2023-11-15 00:00:00','2023-12-01 00:00:00',NULL,NULL,'Equipamentos para apresentação',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(11,'011/GAC-PAC/2024',7,'2024-01-05 00:00:00',NULL,NULL,NULL,'Equipamentos para laboratório',NULL,NULL,NULL,NULL,NULL,'2025-11-06 19:50:09','2025-11-06 19:50:09');
/*!40000 ALTER TABLE `custody_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_assets`
--

DROP TABLE IF EXISTS `inventory_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `asset_id` bigint unsigned NOT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_assets_inventory_id_foreign` (`inventory_id`),
  KEY `inventory_assets_asset_id_foreign` (`asset_id`),
  CONSTRAINT `inventory_assets_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_assets_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventory_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_assets`
--

LOCK TABLES `inventory_assets` WRITE;
/*!40000 ALTER TABLE `inventory_assets` DISABLE KEYS */;
INSERT INTO `inventory_assets` VALUES (1,1,10,'Status: found','2025-11-06 19:50:09','2025-11-06 19:50:09'),(2,1,25,'Status: found','2025-11-06 19:50:09','2025-11-06 19:50:09'),(3,1,39,'Status: found','2025-11-06 19:50:09','2025-11-06 19:50:09'),(4,2,2,'Status: divergence. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(5,2,8,'Status: found. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(6,2,11,'Status: missing. Notes: Não localizado no setor.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(7,2,21,'Status: divergence. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(8,2,29,'Status: missing. Notes: Não localizado no setor.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(9,2,31,'Status: found. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(10,2,32,'Status: found. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(11,3,17,'Status: missing. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(12,3,20,'Status: divergence. Notes: QR Code ilegível.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(13,3,28,'Status: divergence. Notes: QR Code ilegível.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(14,3,29,'Status: divergence. Notes: QR Code ilegível.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(15,3,32,'Status: found. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(16,3,33,'Status: missing. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(17,3,37,'Status: missing. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09'),(18,4,31,'Status: found. Notes: Ativo em cautela com militar.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(19,4,34,'Status: found. Notes: Ativo em cautela com militar.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(20,4,39,'Status: found. Notes: ','2025-11-06 19:50:09','2025-11-06 19:50:09');
/*!40000 ALTER TABLE `inventory_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_records`
--

DROP TABLE IF EXISTS `inventory_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `commission_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `sector_id` bigint unsigned DEFAULT NULL,
  `responsible_user_id` bigint unsigned DEFAULT NULL,
  `status` enum('Concluído','Reaberto','Em Andamento') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Em Andamento',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_records_commission_number_unique` (`commission_number`),
  KEY `inventory_records_sector_id_foreign` (`sector_id`),
  KEY `inventory_records_responsible_user_id_foreign` (`responsible_user_id`),
  CONSTRAINT `inventory_records_responsible_user_id_foreign` FOREIGN KEY (`responsible_user_id`) REFERENCES `military_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_records_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_records`
--

LOCK TABLES `inventory_records` WRITE;
/*!40000 ALTER TABLE `inventory_records` DISABLE KEYS */;
INSERT INTO `inventory_records` VALUES (1,'INV-001/2025','2025-10-27',NULL,7,3,'Em Andamento','Inventário inicial do setor de TI.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(2,'INV-002/2025','2025-10-17','2025-10-22',9,10,'Concluído','Inventário concluído do setor administrativo.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(3,'INV-003/2025','2025-10-07','2025-10-12',7,12,'Reaberto','Inventário reaberto para verificação de divergências.','2025-11-06 19:50:09','2025-11-06 19:50:09'),(4,'INV-004/2025','2025-11-01',NULL,8,3,'Em Andamento','Inventário com ativos em cautela.','2025-11-06 19:50:09','2025-11-06 19:50:09');
/*!40000 ALTER TABLE `inventory_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_records`
--

DROP TABLE IF EXISTS `maintenance_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `performed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_records_asset_id_foreign` (`asset_id`),
  CONSTRAINT `maintenance_records_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_records`
--

LOCK TABLES `maintenance_records` WRITE;
/*!40000 ALTER TABLE `maintenance_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_10_28_233800_create_sectors_table',1),(5,'2025_10_28_233813_create_military_users_table',1),(6,'2025_10_28_233824_create_assets_table',1),(7,'2025_10_28_233832_create_custody_logs_table',1),(8,'2025_10_28_233838_create_custody_assets_table',1),(9,'2025_10_28_233850_create_inventory_records_table',1),(10,'2025_10_28_233857_create_inventory_assets_table',1),(11,'2025_10_28_233906_create_uncatalogued_items_table',1),(12,'2025_10_28_233916_create_reopen_history_table',1),(13,'2025_10_28_233922_create_asset_photos_table',1),(14,'2025_10_28_233929_create_maintenance_records_table',1),(15,'2025_11_03_021915_update_assets_table_for_form_requests',1),(16,'2025_11_03_051114_create_personal_access_tokens_table',1),(17,'2025_11_03_051136_add_auth_fields_to_military_users_table',1),(18,'2025_11_03_070019_add_is_active_to_sectors_table',1),(19,'2025_11_06_014554_make_commission_number_nullable_in_inventory_records_table',1),(20,'2025_11_06_053026_add_signed_document_fields_to_custody_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `military_users`
--

DROP TABLE IF EXISTS `military_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `military_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `military_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sector_id` bigint unsigned DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role` enum('user','commission','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `commission_inventories` json DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `military_users_military_id_unique` (`military_id`),
  KEY `military_users_sector_id_foreign` (`sector_id`),
  CONSTRAINT `military_users_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `military_users`
--

LOCK TABLES `military_users` WRITE;
/*!40000 ALTER TABLE `military_users` DISABLE KEYS */;
INSERT INTO `military_users` VALUES (1,'Ricardo Goulart','Coronel Aviador','111.222.333-01',1,NULL,'$2y$12$vnzBTob76bzCfcNIjnKuhuMbhChGZS4QeLr1J5W2u3fprsbP5z3tO',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:07','2025-11-06 19:50:07'),(2,'Beatriz Almeida','Major Especialista','222.333.444-02',2,NULL,'$2y$12$nwOl16JZacfYxJ8aWu1yEOT0DYuok.ZZBO05km0ZwHKH3r5KdIj5a',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:07','2025-11-06 19:50:07'),(3,'Lucas Martins','Capitão de Infantaria','333.444.555-03',3,NULL,'$2y$12$0gna8gXMwGN1a2lkFN2fU.jDMfrBODzoilJGnAKuA2SOmvQYGLOse',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:07','2025-11-06 19:50:07'),(4,'Juliana Costa','Primeiro-Tenente Intendente','444.555.666-04',6,NULL,'$2y$12$Q807la/btP4rJDZgxVBqfuqdwpViPprlXqa7lsndxzi6LY5MwExHC',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:07','2025-11-06 19:50:07'),(5,'Fernando Oliveira','Segundo-Sargento BCT','555.666.777-05',2,NULL,'$2y$12$if8zFpzP3KiZGwu5WY0rAuZxnKyMAbYRgRli8Vu0.PQMZUZ7QFYhK',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:07','2025-11-06 19:50:07'),(6,'Patrícia Souza','Terceiro-Sargento SAD','666.777.888-06',7,NULL,'$2y$12$MNIW65FP.hNnhW1jV95qbODqc5SZ1XAFLKIhiGC/F9TgUz9tmqKla',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:07','2025-11-06 19:50:07'),(7,'Gustavo Pereira','Cabo','777.888.999-07',5,NULL,'$2y$12$qVjCy86Fy4CpxRVY8nP59ugsa4fHl/KzZDGybX2bRMnt3UpYIgrzW',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:08','2025-11-06 19:50:08'),(8,'Carla Dias','Soldado-de-Primeira-Classe','888.999.000-08',4,NULL,'$2y$12$x78a5WUq2pZLI6yMWXmS7utjQm/QPy4CNjQ28bWk3O.SbMVVqBnni',NULL,NULL,'user',NULL,NULL,0,'2025-11-06 19:50:08','2025-11-06 19:50:08'),(9,'Marcos Lima','Suboficial BCO','999.000.111-09',8,NULL,'$2y$12$aNp/uUMcb80UHPLLnA4ZAOBhXEzLodJEgmd9Hdg3wBbz3liCs5eMa',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:08','2025-11-06 19:50:08'),(10,'Helena Santos','Primeiro-Tenente Analista de Sistemas','000.111.222-10',9,NULL,'$2y$12$hbuKSxAQAXLOVP5w.75Te.fAdpRAP.7fFgorZ.XXqCVgjPV1Cdxf6',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:08','2025-11-06 19:50:08'),(11,'Sérgio Ramos','Cabo','123.123.123-11',10,NULL,'$2y$12$y.F95tumTmCBVcAnPtvEG.jfBA3qmV0zYEQ4Klbi7rmAyWHkd1rmu',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:08','2025-11-06 19:50:08'),(12,'Rafael Andrade','Terceiro-Sargento BCT','234.234.234-12',2,NULL,'$2y$12$tuKDdWKXge0eRXY2lC0WMOWqD.qPTlsbd0dZZa4rGSo1.r4uoCQSu',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:08','2025-11-06 19:50:08'),(13,'Mariana Campos','Cabo','345.345.345-13',2,NULL,'$2y$12$gDrWrzko4kbbEyJ3tQu3SO3z/YZ6skW3qh5uprue5ZaKawh5DkuDi',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(14,'Vanessa Rocha','Primeiro-Tenente Estagiária','456.456.456-14',2,NULL,'$2y$12$O7qpjkdfMG8/B0SwAAzD9.R/doABynRuUsYfJ1iYVlcFeXeKPYgOO',NULL,NULL,'user',NULL,NULL,1,'2025-11-06 19:50:09','2025-11-06 19:50:09'),(15,'Administrador do Sistema','Tenente Coronel','admin001',1,'admin@sgti-gac.mil.br','$2y$12$k5FrxmbhDYyif.MJeGaxjO4je.zrXcipZeehC/uMGd5LyN/LLdNtW',NULL,NULL,'admin',NULL,'(61) 9999-0001',1,'2025-11-06 19:50:10','2025-11-06 19:50:10'),(16,'Oficial de Comissão','Capitão','comissao001',2,'comissao@sgti-gac.mil.br','$2y$12$7jwC4v6rhuYdObuMi9QH7.D/Bop4EsLG8FX5MoVbG49vuNpngOW9W',NULL,NULL,'commission','\"all\"','(61) 9999-0002',1,'2025-11-06 19:50:10','2025-11-06 19:50:10'),(17,'Usuário do Sistema','Sargento','user001',3,'user@sgti-gac.mil.br','$2y$12$/VG9T5KIUIFMYci826qLJeYeUFQ03N3pfkXSaxDLKZm2nz/s/oQ/K',NULL,NULL,'user',NULL,'(61) 9999-0003',1,'2025-11-06 19:50:10','2025-11-06 19:50:10');
/*!40000 ALTER TABLE `military_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reopen_history`
--

DROP TABLE IF EXISTS `reopen_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reopen_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `reopened_by_user_id` bigint unsigned NOT NULL,
  `reopened_at` datetime NOT NULL,
  `justification` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reopen_history_inventory_id_foreign` (`inventory_id`),
  KEY `reopen_history_reopened_by_user_id_foreign` (`reopened_by_user_id`),
  CONSTRAINT `reopen_history_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventory_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reopen_history_reopened_by_user_id_foreign` FOREIGN KEY (`reopened_by_user_id`) REFERENCES `military_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reopen_history`
--

LOCK TABLES `reopen_history` WRITE;
/*!40000 ALTER TABLE `reopen_history` DISABLE KEYS */;
INSERT INTO `reopen_history` VALUES (1,3,8,'2025-10-13 19:50:09','Divergências encontradas na contagem.','2025-11-06 19:50:09','2025-11-06 19:50:09');
/*!40000 ALTER TABLE `reopen_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sectors`
--

DROP TABLE IF EXISTS `sectors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sectors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sectors`
--

LOCK TABLES `sectors` WRITE;
/*!40000 ALTER TABLE `sectors` DISABLE KEYS */;
INSERT INTO `sectors` VALUES (1,'CHF','Chefia',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(2,'ATI','Assessoria de Tecnologia da Informação',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(3,'AIT','Assessoria de Inteligência',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(4,'SEC','Secretaria',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(5,'ALOG','Assessoria Logística',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(6,'SFI','Seção Financeira',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(7,'SAD','Seção Administrativa',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(8,'STEC','Seção Técnica',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(9,'SCP-SIS','Seção de Coordenação de Projetos e Sistemas',1,'2025-11-06 19:50:06','2025-11-06 19:50:06'),(10,'Almoxarifado TI','Depósito de material de TI',1,'2025-11-06 19:50:06','2025-11-06 19:50:06');
/*!40000 ALTER TABLE `sectors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('b72DRcI564OLnSaj8yiiPiNutSVtgcIPLccmWZcS',NULL,'172.18.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibDdTZEVIdGlMdnFiOGw4bmkwcWlIWU9wQlhSdnlnbGp0eWJNQ2lzVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6NTA1MC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762459484),('dmmUMYCEkPJJ39wHXM75476z6kkfyAnT4gi6v74q',NULL,'172.18.0.1','curl/8.5.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoiZEhSdEJlQkdSTzdRSkdDdDd6YkdLYTd6M1JQTlZVZ2dtQ3RVQ0w4TSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762458620),('scO6bKLutxT2oYsWwt2668cd7REBHG376ySdGPF7',NULL,'172.18.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiaFpOaUhLNlZkUGxCRktKOWQ3b2pJV00ydWNERndkMWZFanhlVnBHUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762459546);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uncatalogued_items`
--

DROP TABLE IF EXISTS `uncatalogued_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uncatalogued_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `found_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uncatalogued_items_inventory_id_foreign` (`inventory_id`),
  CONSTRAINT `uncatalogued_items_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventory_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uncatalogued_items`
--

LOCK TABLES `uncatalogued_items` WRITE;
/*!40000 ALTER TABLE `uncatalogued_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `uncatalogued_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-06 20:08:15
