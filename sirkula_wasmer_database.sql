-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: nuevaweb
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
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
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Kaos & T-Shirt','kaos-t-shirt','2026-10-15 18:30:24','2026-10-15 18:30:24'),(2,'Kemeja Casual','kemeja','2026-10-15 18:30:24','2026-10-20 17:35:29'),(3,'Hoodie & Jaket','hoodie-jaket','2026-10-15 18:30:24','2026-10-15 18:30:24'),(4,'Outerwear & Jaket','outerwear-jaket','2026-10-20 17:35:29','2026-10-20 17:35:29'),(5,'Celana & Bawahan','celana-bawahan','2026-10-20 17:35:29','2026-10-20 17:35:29');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
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
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
  `attempts` smallint unsigned NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_16_005742_add_role_to_users_table',2),(5,'2026_10_16_010028_create_categories_table',2),(6,'2026_10_16_010228_create_products_table',2),(7,'2026_10_16_010456_create_product_variants_table',2),(8,'2026_10_19_031706_create_orders_table',3),(9,'2026_10_19_031717_create_order_items_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_variant_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_info` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` bigint unsigned NOT NULL,
  `subtotal` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,3,'Heavyweight Boxy T-Shirt','Warna: Hitam | Ukuran: M',1,129000,129000,'2026-10-18 20:34:45','2026-10-18 20:34:45');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `total_amount` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_notes` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank_transfer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-20261019-2GRTB',2,129000,'cancelled','Budi S','081234567890','Jl. Jakarta Nomor XVII',NULL,'bca','2026-10-18 20:34:45','2026-10-18 21:53:33');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_price` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,'S','Hitam',12,'HEA-HIT-S',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(2,1,'S','Putih',12,'HEA-PUT-S',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(3,1,'M','Hitam',12,'HEA-HIT-M',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(4,1,'M','Putih',12,'HEA-PUT-M',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(5,1,'L','Hitam',12,'HEA-HIT-L',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(6,1,'L','Putih',12,'HEA-PUT-L',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(7,1,'XL','Hitam',12,'HEA-HIT-XL',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(8,1,'XL','Putih',12,'HEA-PUT-XL',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(9,2,'M','Navy Plaid',7,'FLA-NAV-M',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(10,2,'L','Navy Plaid',7,'FLA-NAV-L',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(11,2,'XL','Navy Plaid',7,'FLA-NAV-XL',0,'2026-10-15 18:30:24','2026-10-20 17:35:29'),(12,1,'S','Sage Green',12,'HEA-SAG-S',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(13,1,'M','Sage Green',12,'HEA-SAG-M',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(14,1,'L','Sage Green',12,'HEA-SAG-L',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(15,1,'XL','Sage Green',12,'HEA-SAG-XL',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(16,3,'M','Charcoal Faded',8,'VIN-CHA-M',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(17,3,'L','Charcoal Faded',8,'VIN-CHA-L',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(18,3,'XL','Charcoal Faded',8,'VIN-CHA-XL',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(19,3,'M','Khaki Sand',8,'VIN-KHA-M',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(20,3,'L','Khaki Sand',8,'VIN-KHA-L',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(21,3,'XL','Khaki Sand',8,'VIN-KHA-XL',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(22,2,'S','Navy Plaid',7,'FLA-NAV-S',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(23,2,'S','Forest Green Plaid',7,'FLA-FOR-S',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(24,2,'M','Forest Green Plaid',7,'FLA-FOR-M',0,'2026-10-20 17:35:29','2026-10-20 17:35:29'),(25,2,'L','Forest Green Plaid',7,'FLA-FOR-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(26,2,'XL','Forest Green Plaid',7,'FLA-FOR-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(27,4,'M','Broken White',6,'LIN-BRO-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(28,4,'L','Broken White',6,'LIN-BRO-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(29,4,'XL','Broken White',6,'LIN-BRO-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(30,4,'M','Terracotta',6,'LIN-TER-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(31,4,'L','Terracotta',6,'LIN-TER-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(32,4,'XL','Terracotta',6,'LIN-TER-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(33,5,'M','Brown Duck',5,'HEA-BRO-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(34,5,'L','Brown Duck',5,'HEA-BRO-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(35,5,'XL','Brown Duck',5,'HEA-BRO-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(36,5,'M','Deep Navy',5,'HEA-DEE-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(37,5,'L','Deep Navy',5,'HEA-DEE-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(38,5,'XL','Deep Navy',5,'HEA-DEE-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(39,6,'S','Oatmeal Heather',9,'MIN-OAT-S',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(40,6,'M','Oatmeal Heather',9,'MIN-OAT-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(41,6,'L','Oatmeal Heather',9,'MIN-OAT-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(42,6,'XL','Oatmeal Heather',9,'MIN-OAT-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(43,6,'S','Solid Black',9,'MIN-SOL-S',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(44,6,'M','Solid Black',9,'MIN-SOL-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(45,6,'L','Solid Black',9,'MIN-SOL-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(46,6,'XL','Solid Black',9,'MIN-SOL-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(47,7,'S','Olive Drab',6,'PLE-OLI-S',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(48,7,'M','Olive Drab',6,'PLE-OLI-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(49,7,'L','Olive Drab',6,'PLE-OLI-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(50,7,'XL','Olive Drab',6,'PLE-OLI-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(51,7,'S','Charcoal Grey',6,'PLE-CHA-S',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(52,7,'M','Charcoal Grey',6,'PLE-CHA-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(53,7,'L','Charcoal Grey',6,'PLE-CHA-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(54,7,'XL','Charcoal Grey',6,'PLE-CHA-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(55,8,'M','Dark Indigo',8,'REL-DAR-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(56,8,'L','Dark Indigo',8,'REL-DAR-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(57,8,'XL','Dark Indigo',8,'REL-DAR-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(58,8,'M','Washed Blue',8,'REL-WAS-M',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(59,8,'L','Washed Blue',8,'REL-WAS-L',0,'2026-10-20 17:35:30','2026-10-20 17:35:30'),(60,8,'XL','Washed Blue',8,'REL-WAS-XL',0,'2026-10-20 17:35:30','2026-10-20 17:35:30');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `base_price` bigint unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Heavyweight Boxy T-Shirt','heavyweight-boxy-t-shirt','Kaos katun premium 24s dengan siluet boxy fit kontemporer. Telah melewati proses sanitasi ganda (deep steam & ozon) berstandar SirkulaCare.\n\nBahan: 100% Cotton Combed Heavyweight 240 GSM\nKondisi: Grade A+ (Like New, tanpa cacat)\nUkuran Fisik (Flat-lay M): Lebar Dada 56 cm, Panjang 70 cm.',129000,'products/tshirt-boxy-1.jpg',1,'2026-10-15 18:30:24','2026-10-20 18:27:18'),(2,2,'Flannel Casual Long Sleeve Shirt','flannel-casual-long-sleeve-shirt','Kemeja flanel lengan panjang motif tartan klasik dengan serat berbulu halus yang hangat dan nyaman. Cocok untuk outer harian.\n\nBahan: 100% Brushed Cotton Flannel\nKondisi: Grade A+ (Like New, kancing orisinal lengkap)\nUkuran Fisik (Flat-lay L): Lebar Dada 55 cm, Panjang Lengan 62 cm, Panjang Badan 73 cm.',189000,'products/flannel-1.jpg',1,'2026-10-15 18:30:24','2026-10-20 18:27:18'),(3,1,'Vintage Washed Oversized Tee','vintage-washed-oversized-tee','Kaos efek acid/faded wash dengan tekstur jatuh yang lembut dan estetika streetwear vintage. Hasil kurasi batch pabrik garmen ekspor.\n\nBahan: 100% Pre-washed Cotton 20s\nKondisi: Grade A (Sangat Baik, warna pudar alami estetik)\nUkuran Fisik (Flat-lay L): Lebar Dada 58 cm, Panjang 72 cm.',119000,'products/crewneck-1.jpg',1,'2026-10-20 17:35:29','2026-10-20 18:27:18'),(4,2,'Linen Blend Camp Collar Shirt','linen-blend-camp-collar-shirt','Kemeja santai kerah terbuka (camp collar) berbahan campuran linen alami yang breathable untuk iklim tropis. Potongan rileks.\n\nBahan: 55% Linen Alami, 45% Katun\nKondisi: Grade A+ (Pabrik Overrun tanpa cacat)\nUkuran Fisik (Flat-lay M): Lebar Dada 54 cm, Panjang 69 cm.',179000,'products/linen-shirt-1.jpg',1,'2026-10-20 17:35:30','2026-10-20 18:27:18'),(5,4,'Heavy Cotton Canvas Workwear Jacket','heavy-cotton-canvas-workwear-jacket','Jaket kerja kanvas katun tebal dengan kerah corduroy kontras dan resleting kuningan YKK. Material tangguh dan tahan lama.\n\nBahan: 100% Heavyweight Cotton Canvas 12oz\nKondisi: Grade A (Arsip kurasi vintage terpilih)\nUkuran Fisik (Flat-lay L): Lebar Dada 60 cm, Panjang 68 cm.',279000,'products/worker-jacket-1.jpg',1,'2026-10-20 17:35:30','2026-10-20 18:27:18'),(6,4,'Minimalist Zip-Up Fleece Hoodie','minimalist-zip-up-fleece-hoodie','Hoodie fleece beresleting penuh dengan bagian dalam berbulu lembut dan potongan bahu drop shoulder. Hangat dan praktis.\n\nBahan: 80% Katun, 20% Poliester Daur Ulang\nKondisi: Grade A+ (Like New, karet rib kencang)\nUkuran Fisik (Flat-lay L): Lebar Dada 59 cm, Panjang 71 cm.',229000,'products/denim-jacket-1.jpg',1,'2026-10-20 17:35:30','2026-10-20 18:27:18'),(7,5,'Pleated Wide-Leg Trousers','pleated-wide-leg-trousers','Celana panjang potongan wide-leg dengan aksen lipit (pleats) ganda di pinggang depan. Memberikan siluet drape yang rapi dan elegan.\n\nBahan: Semi-Wool Polyester Blend\nKondisi: Grade A+ (QC Passed)\nUkuran Fisik (Size 32): Lingkar Pinggang 82 cm, Panjang 102 cm, Open Leg 23 cm.',199000,'products/chino-pants-1.jpg',1,'2026-10-20 17:35:30','2026-10-20 18:27:18'),(8,5,'Relaxed Fit Raw Denim Jeans','relaxed-fit-raw-denim-jeans','Celana jeans selvedge-accent potongan lurus rileks. Siap membentuk fading unik seiring pemakaian oleh pemilik baru.\n\nBahan: 100% Rigid Raw Cotton Denim 13.5oz\nKondisi: Grade A+ (Unwashed Deadstock)\nUkuran Fisik (Size 32): Lingkar Pinggang 84 cm, Panjang 104 cm.',249000,'products/ankle-pants-1.jpg',1,'2026-10-20 17:35:30','2026-10-20 18:27:18');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('076KgEzuoEixCFAkfSlQhIHUfP9JMC7LYpH6loyr',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiIxQ01XU0F2Tm1TSVJIcjJDM2tRdkV2bFIwRnpNdmNxMDh5VlVSbGozIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbnVldmF3ZWIudGVzdFwvZGFzaGJvYXJkIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9udWV2YXdlYi50ZXN0XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1792393454),('9ohecGWXTFiAVGnsKg27SPi5x9C3tpNbSFKu3JTP',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJCTWhLY2dUYXFPUUhjRXRnOFp3MXVNd01NbklvYXdZOHJOWmJzaDFOIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL251ZXZhd2ViLnRlc3QiLCJyb3V0ZSI6InByb2R1Y3RzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792444500),('a5Qr8PqMqL7B5V8cr2FYfgoXSC1lFemb9sAI7fQH',NULL,'127.0.0.1','','eyJfdG9rZW4iOiJoaXJNZEk5bEc1Nnl4VWJ1RjRncXR2V0VOZURHT3NVbjRnZlNXTmd1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJwcm9kdWN0cy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1792546344),('F36uYC3hJtUBYA0APdVmv1IpCnt0NV253FlZxNkv',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJOSjNJSkYzcENTck55NmRybWtSQ3ZwT0ltZ3JFNjVoTjdsNXBxMkdHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL251ZXZhd2ViLnRlc3RcL2FkbWluXC9vcmRlcnMiLCJyb3V0ZSI6ImFkbWluLm9yZGVycy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6ImFkMmMyZmNjMWFhMjE4NzM3NzcwZDljMjFjNmZlYmZhNzVkZjhmYjIyODFlZjQ0YWMzYWMwYjJkMzI0MGM0M2QifQ==',1792385614),('h3fssmMhzD4JAGTwfyZRMMuUWwWdz5LNELVvPwmn',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJMMk5hMmpVbTZBNlBXS3J3NTlkTVNpZHhHSk9nVjRPVFVQcmpEVk1LIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL251ZXZhd2ViLnRlc3QiLCJyb3V0ZSI6InByb2R1Y3RzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792444500),('i8khUFq8LbuCijsF8BPgpRoRaUoL5Uqr1ECDEoEQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJuMVFBV0VyWXFkZUdqd0xWclcxSjl5c0VwWkRFWW9HR1gwUjhKaDl6IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9jYXJ0Iiwicm91dGUiOiJjYXJ0LmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwiY2FydCI6eyIzIjp7InZhcmlhbnRfaWQiOjMsInByb2R1Y3RfbmFtZSI6IkhlYXZ5d2VpZ2h0IEJveHkgVC1TaGlydCIsInNsdWciOiJoZWF2eXdlaWdodC1ib3h5LXQtc2hpcnQiLCJjYXRlZ29yeSI6Ikthb3MgJiBULVNoaXJ0IiwiaW1hZ2UiOm51bGwsImNvbG9yIjoiSGl0YW0iLCJzaXplIjoiTSIsInByaWNlIjoxMjkwMDAsInF1YW50aXR5IjoxLCJtYXhfc3RvY2siOjEwfX19',1792379682),('Jd2RcsgV4gHodvJvPvSXkqjMf1gH77r6SytfQ7FX',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ4eEttcldPZWtkckFrUVFLT3BPZGlDVFBJZDFFbVJhSmpQUkVPV3ZkIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbnVldmF3ZWIudGVzdFwvYWRtaW5cL29yZGVycyIsInJvdXRlIjoiYWRtaW4ub3JkZXJzLmluZGV4In0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6IjYxZjQ3YjQ4OTRkMDJiNTdiOWI1MmQwOGU5ZjFmNjc5NGMyNmRiMjY4Yzk3M2ZhNzkwMzBiMTFmZmQ4YWE5NDQifQ==',1792546917),('P5aZnTVi93h7uT7HaHwJzbwEv0b0OexkBRX5ODxh',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiIzZ0R5WDhRaGVTTWs2TXU5NktHSDVZc1JrOU1NRjdNSVhybVRpcUdmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL251ZXZhd2ViLnRlc3QiLCJyb3V0ZSI6InByb2R1Y3RzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiNWUxMDUwYTQ2YjViNDVmZmEzMjVmNWQxODYxNTM4NTE3N2FlNzcyMmU5N2U1OTdkMDZhNWJiYmZhODMzZjhiNyJ9',1792395381),('SG1HzfrqAfle2BT9coZOKnMmaW1I4xv1TviCdoI8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJVaXkycEU1eDViblZqdE1oRVFoUU5OZ0V6ZE5pejlreHc0OUxqVlBVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL251ZXZhd2ViLnRlc3QiLCJyb3V0ZSI6InByb2R1Y3RzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792444501),('UDIDUGehQRw1FK7Bhhtr8kkjfvijSTfzfnD2RpDQ',NULL,'127.0.0.1','','eyJfdG9rZW4iOiJ0UWhvc2pvSVdSUHFnMmJESkpjTUs2UHpNdVBDOG1nSVlMWm83ZmRBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wcm9kdWN0c1wvaGVhdnl3ZWlnaHQtYm94eS10LXNoaXJ0Iiwicm91dGUiOiJwcm9kdWN0cy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792546359);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
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
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Sirkula','admin@toko.com',NULL,'$2y$12$c8ReUAu0N1Q8NbwpVyAZSu5dRGai9fZCbIQwXilJB4KWkFM.60TE2','admin',NULL,'2026-10-15 18:30:23','2026-10-20 18:27:18'),(2,'Budi Pembeli','budi@gmail.com',NULL,'$2y$12$Bkzn/uvPp9mZdv7H9zZ99OEAmhQkT2M.jAXgQn8NShyquarJ3n73.','customer',NULL,'2026-10-15 18:30:24','2026-10-20 18:27:18');
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

-- Dump completed on 2026-10-21 10:59:01
