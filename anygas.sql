CREATE DATABASE  IF NOT EXISTS `anygas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `anygas`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: anygas
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned DEFAULT NULL,
  `usuario_nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo_id` bigint unsigned DEFAULT NULL,
  `datos_viejos` json DEFAULT NULL,
  `datos_nuevos` json DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'Diego Alonso Alvarado','updated','App\\Models\\Usuario',4,'\"{\\\"correo\\\":\\\"motorizado1@gmail.com\\\"}\"','\"{\\\"correo\\\":\\\"motorizado11@gmail.com\\\"}\"','127.0.0.1','2026-07-13 11:00:47');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
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
-- Table structure for table `cliente_direcciones`
--

DROP TABLE IF EXISTS `cliente_direcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_direcciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `etiqueta` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Principal',
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_direcciones_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_direcciones_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_direcciones`
--

LOCK TABLES `cliente_direcciones` WRITE;
/*!40000 ALTER TABLE `cliente_direcciones` DISABLE KEYS */;
INSERT INTO `cliente_direcciones` VALUES (1,1,'Av','',NULL,NULL,'Principal',1,'2026-07-09 21:14:01','2026-07-09 21:14:01'),(2,2,'Av Principal 666','',NULL,NULL,'Principal',1,'2026-07-09 21:14:01','2026-07-09 21:14:01'),(3,3,'Av Principal 888','',NULL,NULL,'Principal',1,'2026-07-09 21:14:01','2026-07-09 21:14:01'),(4,2,'Jose Olaya 852','',NULL,NULL,'Dirección 2',0,'2026-07-10 02:17:21','2026-07-10 02:17:21'),(5,4,'Los Alisos 111','',NULL,NULL,'Dirección 1',1,'2026-07-10 02:23:56','2026-07-10 02:23:56'),(6,4,'Mayolo 1012','',NULL,NULL,'Dirección 2',0,'2026-07-12 06:10:59','2026-07-12 06:10:59'),(7,5,'Av Holando 111 (en la invasion)','',NULL,NULL,'Dirección 1',1,'2026-07-12 06:37:20','2026-07-12 06:37:20'),(8,6,'Av. Universitaria Nro. 4295','',NULL,NULL,'Dirección 1',1,'2026-07-12 06:56:59','2026-07-12 06:56:59'),(9,6,'AV. UNIVERSITARIA NRO. 4295, LIMA - LIMA - SAN MARTIN DE PORRES','',NULL,NULL,'Dirección 2',0,'2026-07-12 07:18:55','2026-07-12 07:18:55'),(10,7,'AV. UNIVERSITARIA NRO. 4295, LIMA - LIMA - SAN MARTIN DE PORRES','',NULL,NULL,'Dirección 1',1,'2026-07-12 07:21:10','2026-07-12 07:21:10');
/*!40000 ALTER TABLE `cliente_direcciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_telefonos`
--

DROP TABLE IF EXISTS `cliente_telefonos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_telefonos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etiqueta` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. Principal, Trabajo, Vecino',
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_telefonos_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_telefonos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_telefonos`
--

LOCK TABLES `cliente_telefonos` WRITE;
/*!40000 ALTER TABLE `cliente_telefonos` DISABLE KEYS */;
INSERT INTO `cliente_telefonos` VALUES (1,2,'924484038','Vecino',0,'2026-07-12 06:23:34','2026-07-12 06:23:34');
/*!40000 ALTER TABLE `cliente_telefonos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documento_identidad` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL COMMENT 'DNI o RUC',
  `nombres` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `direccion_principal` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `referencia_direccion` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `estado` enum('activo','inactivo','moroso') COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'activo',
  `deuda_envases` int NOT NULL DEFAULT '0' COMMENT 'Balones vacíos no devueltos',
  `notas_internas` text COLLATE utf8mb4_spanish_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_clientes_documento` (`documento_identidad`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'71526042','Ivan Placido','','924484038','Av','',NULL,'activo',0,NULL),(2,'09050812','Ivan Placido','','999888777','Av Principal 666','',NULL,'activo',0,NULL),(3,'090587321','Joselyn Huarcaya','','987654321','Av Principal 888','',NULL,'activo',0,NULL),(4,'71526041','BRAYAN JESUS','Palomino Ayala','999888772','Los Alisos 111',NULL,NULL,'activo',0,NULL),(5,'74811380','Mandilon Montoro','','936356400','Av Holando 111 (en la invasion)','',NULL,'activo',0,NULL),(6,'20565258664','Joaquin','Palomares Medina','951632478','Av. Universitaria Nro. 4295',NULL,NULL,'activo',0,NULL),(7,'20565258665','HOTEL WAYRA TOURS SOCIEDAD ANONIMA CERRADA - HOTEL WAYRA TOURS S.A.C.','','963852741','AV. UNIVERSITARIA NRO. 4295, LIMA - LIMA - SAN MARTIN DE PORRES','',NULL,'activo',0,NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comprobantes`
--

DROP TABLE IF EXISTS `comprobantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comprobantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint unsigned NOT NULL,
  `tipo_comprobante` enum('boleta','factura') COLLATE utf8mb4_spanish_ci NOT NULL,
  `serie` varchar(10) COLLATE utf8mb4_spanish_ci NOT NULL,
  `numero_correlativo` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `monto_total` decimal(8,2) NOT NULL DEFAULT '0.00',
  `estado_sincronizacion` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_comprobantes_pedido` (`pedido_id`),
  CONSTRAINT `fk_comprobantes_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comprobantes`
--

LOCK TABLES `comprobantes` WRITE;
/*!40000 ALTER TABLE `comprobantes` DISABLE KEYS */;
INSERT INTO `comprobantes` VALUES (1,1,'boleta','B001','000001','2026-06-29 07:03:57',748.50,'aceptado'),(2,2,'boleta','B001','000002','2026-07-09 20:10:30',475.00,'pendiente'),(3,3,'boleta','B001','000003','2026-07-09 20:10:30',475.00,'pendiente'),(4,4,'boleta','B001','000004','2026-07-09 20:10:30',475.00,'pendiente'),(5,5,'boleta','B001','000005','2026-07-09 20:32:11',55.00,'pendiente'),(6,6,'boleta','B001','000006','2026-07-09 21:03:48',46.00,'pendiente'),(7,7,'boleta','B001','000007','2026-07-09 21:12:46',52.50,'pendiente'),(8,8,'boleta','B001','000008','2026-07-09 21:14:57',52.50,'pendiente'),(9,9,'boleta','B001','000009','2026-07-09 21:15:21',78.00,'pendiente'),(10,10,'boleta','B001','000010','2026-07-09 21:17:21',52.50,'pendiente'),(11,11,'boleta','B001','000011','2026-07-09 21:23:56',52.00,'pendiente'),(12,12,'boleta','B001','000012','2026-07-12 01:10:59',75.00,'pendiente'),(13,13,'boleta','B001','000013','2026-07-12 01:37:20',70.00,'aceptado'),(14,14,'factura','F001','000014','2026-07-12 01:56:59',630.00,'aceptado'),(15,15,'factura','F001','000015','2026-07-12 02:18:55',53.00,'pendiente'),(16,16,'factura','F001','000016','2026-07-12 02:21:10',28.00,'pendiente');
/*!40000 ALTER TABLE `comprobantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_pedido`
--

DROP TABLE IF EXISTS `detalles_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalles_pedido` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(8,2) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL,
  `envases_devueltos` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_detalles_pedido_pedido` (`pedido_id`),
  KEY `fk_detalles_pedido_producto` (`producto_id`),
  CONSTRAINT `fk_detalles_pedido_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detalles_pedido_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_pedido`
--

LOCK TABLES `detalles_pedido` WRITE;
/*!40000 ALTER TABLE `detalles_pedido` DISABLE KEYS */;
INSERT INTO `detalles_pedido` VALUES (1,1,1,1,28.50,28.50,0),(2,1,2,1,52.00,52.00,0),(3,1,3,2,55.00,110.00,0),(4,1,5,1,210.00,210.00,0),(5,1,6,1,27.00,27.00,0),(6,1,10,1,50.00,50.00,0),(7,1,11,1,53.00,53.00,0),(8,1,17,1,35.00,35.00,0),(9,1,18,1,28.00,28.00,0),(10,1,19,1,42.00,42.00,0),(11,1,20,1,8.00,8.00,0),(12,1,22,1,19.00,19.00,0),(13,1,23,1,28.00,28.00,0),(14,1,26,1,58.00,58.00,0),(15,2,2,3,52.00,156.00,0),(16,2,3,1,55.00,55.00,0),(17,2,5,1,210.00,210.00,0),(18,2,6,2,27.00,54.00,0),(19,3,2,3,52.00,156.00,0),(20,3,3,1,55.00,55.00,0),(21,3,5,1,210.00,210.00,0),(22,3,6,2,27.00,54.00,0),(23,4,2,3,52.00,156.00,0),(24,4,3,1,55.00,55.00,0),(25,4,5,1,210.00,210.00,0),(26,4,6,2,27.00,54.00,0),(27,5,3,1,55.00,55.00,0),(28,6,7,1,45.00,45.00,0),(29,6,24,1,1.00,1.00,0),(30,7,8,1,52.50,52.50,0),(31,8,8,1,52.50,52.50,1),(32,9,4,1,78.00,78.00,0),(33,10,8,1,52.50,52.50,0),(34,11,2,1,52.00,52.00,0),(35,12,4,1,75.00,75.00,0),(36,13,2,1,50.00,50.00,0),(37,13,16,1,20.00,20.00,0),(38,14,5,3,210.00,630.00,0),(39,15,11,1,53.00,53.00,0),(40,16,18,1,28.00,28.00,0);
/*!40000 ALTER TABLE `detalles_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_reabastecimiento`
--

DROP TABLE IF EXISTS `detalles_reabastecimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalles_reabastecimiento` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reabastecimiento_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad_recibida` int NOT NULL,
  `costo_unitario_compra` decimal(8,2) NOT NULL,
  `subtotal_compra` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detalles_reab_cabecera` (`reabastecimiento_id`),
  KEY `fk_detalles_reab_producto` (`producto_id`),
  CONSTRAINT `fk_detalles_reab_cabecera` FOREIGN KEY (`reabastecimiento_id`) REFERENCES `reabastecimientos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detalles_reab_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_reabastecimiento`
--

LOCK TABLES `detalles_reabastecimiento` WRITE;
/*!40000 ALTER TABLE `detalles_reabastecimiento` DISABLE KEYS */;
INSERT INTO `detalles_reabastecimiento` VALUES (1,1,7,12,40.50,486.00),(2,2,7,3,32.00,96.00);
/*!40000 ALTER TABLE `detalles_reabastecimiento` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_09_202539_add_imagen_and_descripcion_to_productos_table',2),(5,'2026_07_09_203546_add_fecha_salida_to_pedidos_table',3),(6,'2026_07_09_210039_add_precio_compra_to_productos_table',4),(7,'2026_07_09_211336_create_cliente_direcciones_table',5),(8,'2026_07_10_061821_add_deuda_envases_to_clientes_table',6),(9,'2026_07_10_061821_create_cliente_telefonos_table',6),(10,'2026_07_10_062518_add_notas_internas_to_clientes_table',7),(11,'2026_07_11_000001_add_estado_sincronizacion_to_comprobantes_table',8),(12,'2026_07_13_040613_update_estado_enum_in_pedidos',9),(13,'2026_07_13_051716_add_envases_devueltos_to_detalles_pedido_table',10),(14,'2026_07_13_060000_create_core_business_tables',11),(15,'2026_07_13_070000_drop_users_table',11),(16,'2026_07_05_000000_create_core_business_tables',12),(17,'2026_07_13_080000_add_monto_total_to_comprobantes_table',12),(18,'2026_07_13_090000_create_audit_logs_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos_pedido`
--

DROP TABLE IF EXISTS `pagos_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos_pedido` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint unsigned NOT NULL,
  `tipo_pago_id` bigint unsigned NOT NULL,
  `monto` decimal(8,2) NOT NULL,
  `monto_recibido` decimal(8,2) DEFAULT NULL COMMENT 'Para calcular el vuelto en efectivo',
  PRIMARY KEY (`id`),
  KEY `fk_pagos_pedido_pedido` (`pedido_id`),
  KEY `fk_pagos_pedido_tipo` (`tipo_pago_id`),
  CONSTRAINT `fk_pagos_pedido_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pagos_pedido_tipo` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipos_pago` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos_pedido`
--

LOCK TABLES `pagos_pedido` WRITE;
/*!40000 ALTER TABLE `pagos_pedido` DISABLE KEYS */;
INSERT INTO `pagos_pedido` VALUES (1,1,1,748.50,748.50),(2,2,1,475.00,475.00),(3,3,1,475.00,475.00),(4,4,1,475.00,475.00),(5,5,1,55.00,55.00),(6,6,1,46.00,46.00),(7,7,1,52.50,52.50),(8,8,1,52.50,52.50),(9,9,1,78.00,78.00),(10,10,1,52.50,52.50),(11,11,1,52.00,52.00),(12,12,2,75.00,75.00),(13,13,1,70.00,70.00),(14,14,1,630.00,630.00),(15,15,1,53.00,53.00),(16,16,1,28.00,28.00),(17,1,1,48.00,50.00),(18,1,3,300.00,NULL),(19,1,4,200.00,NULL),(20,1,2,200.50,NULL),(21,6,1,10.00,50.00),(22,6,3,36.00,NULL),(23,5,1,55.00,NULL),(24,7,3,52.50,NULL),(25,8,1,52.50,NULL);
/*!40000 ALTER TABLE `pagos_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo_seguimiento` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `cliente_id` bigint unsigned NOT NULL,
  `recepcionista_id` bigint unsigned NOT NULL,
  `motorizado_id` bigint unsigned DEFAULT NULL,
  `direccion_entrega` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `referencia_entrega` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `tipo_despacho` enum('domicilio','recojo_tienda') COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'domicilio',
  `monto_total` decimal(8,2) NOT NULL,
  `estado` enum('pendiente','asignado','en_camino','en_ruta','entregado','cancelado') COLLATE utf8mb4_spanish_ci DEFAULT 'pendiente',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_salida` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pedidos_codigo` (`codigo_seguimiento`),
  KEY `fk_pedidos_cliente` (`cliente_id`),
  KEY `fk_pedidos_recepcionista` (`recepcionista_id`),
  KEY `fk_pedidos_motorizado` (`motorizado_id`),
  CONSTRAINT `fk_pedidos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pedidos_motorizado` FOREIGN KEY (`motorizado_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_pedidos_recepcionista` FOREIGN KEY (`recepcionista_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,'ANG-7405',1,2,3,'Av','','domicilio',748.50,'entregado','2026-06-29 07:03:57','2026-07-13 04:07:37','2026-07-13 04:34:22'),(2,'ANG-5930',1,2,5,'Av Principal 127','','domicilio',475.00,'asignado','2026-07-09 20:10:30','2026-07-09 20:43:04',NULL),(3,'ANG-7355',1,2,4,'Av Principal 127','','domicilio',475.00,'asignado','2026-07-09 20:10:30','2026-07-09 20:45:51',NULL),(4,'ANG-8356',1,2,5,'Av Principal 127','','domicilio',475.00,'asignado','2026-07-09 20:10:30','2026-07-09 20:44:39',NULL),(5,'ANG-8604',2,2,3,'Av Principal 666','','domicilio',55.00,'entregado','2026-07-09 20:32:10','2026-07-13 05:13:56','2026-07-13 05:14:01'),(6,'ANG-9632',3,2,3,'Av Principal 888','','domicilio',46.00,'entregado','2026-07-09 21:03:48','2026-07-13 04:08:24','2026-07-13 05:04:37'),(7,'ANG-2714',2,2,3,'Av Principal 774','','domicilio',52.50,'entregado','2026-07-09 21:12:46','2026-07-13 05:13:57','2026-07-13 05:14:12'),(8,'ANG-9594',2,2,3,'San Juan de Dios 152','','domicilio',52.50,'entregado','2026-07-09 21:14:57','2026-07-13 05:14:16','2026-07-13 05:14:31'),(9,'ANG-6842',2,2,3,'Av Arequipes 852','','domicilio',78.00,'asignado','2026-07-09 21:15:21','2026-07-13 05:13:46',NULL),(10,'ANG-3490',2,2,3,'Jose Olaya 852','','domicilio',52.50,'asignado','2026-07-09 21:17:21','2026-07-13 05:13:43',NULL),(11,'ANG-8092',4,2,NULL,'Los Alisos 111','','domicilio',52.00,'pendiente','2026-07-09 21:23:56',NULL,NULL),(12,'ANG-2401',4,1,4,'Mayolo 1012','','domicilio',75.00,'asignado','2026-07-12 01:10:59','2026-07-12 01:11:15',NULL),(13,'ANG-3909',5,1,NULL,'Av Holando 111 (en la invasion)','','domicilio',70.00,'pendiente','2026-07-12 01:37:20',NULL,NULL),(14,'ANG-2345',6,1,NULL,'Av. Universitaria Nro. 4295','','domicilio',630.00,'pendiente','2026-07-12 01:56:59',NULL,NULL),(15,'ANG-9690',6,1,NULL,'AV. UNIVERSITARIA NRO. 4295, LIMA - LIMA - SAN MARTIN DE PORRES','','domicilio',53.00,'pendiente','2026-07-12 02:18:55',NULL,NULL),(16,'ANG-9696',7,1,NULL,'AV. UNIVERSITARIA NRO. 4295, LIMA - LIMA - SAN MARTIN DE PORRES','','domicilio',28.00,'pendiente','2026-07-12 02:21:10',NULL,NULL);
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proveedor_id` bigint unsigned NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `marca` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci,
  `tipo_entrada` enum('estandar','premium','ninguna') COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'ninguna',
  `precio_venta` decimal(8,2) NOT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `stock_actual` int NOT NULL DEFAULT '0',
  `estado` enum('disponible','agotado','descontinuado') COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'disponible',
  PRIMARY KEY (`id`),
  KEY `fk_productos_proveedor` (`proveedor_id`),
  CONSTRAINT `fk_productos_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,1,'Balón de Gas 5 KG','Solgas','productos/placeholder_1.svg',NULL,'estandar',28.50,20.00,34,'disponible'),(2,1,'Balón de Gas 10 KG','Solgas','productos/placeholder_2.svg',NULL,'estandar',52.00,43.00,98,'disponible'),(3,1,'Balón de Gas 10 KG','Solgas','productos/placeholder_3.svg',NULL,'premium',55.00,46.00,69,'disponible'),(4,1,'Balón de Gas 15 KG','Solgas','productos/placeholder_4.svg',NULL,'estandar',78.00,69.00,18,'disponible'),(5,1,'Balón de Gas 45 KG Industrial','Solgas','productos/placeholder_5.svg',NULL,'estandar',210.00,200.00,5,'disponible'),(6,2,'Balón de Gas 5 KG','Costagas','productos/placeholder_6.svg',NULL,'estandar',27.00,18.50,18,'disponible'),(7,2,'Balón de Gas 10 KG','Costagas','productos/placeholder_7.svg',NULL,'estandar',49.50,40.50,99,'disponible'),(8,2,'Balón de Gas 10 KG','Costagas','productos/placeholder_8.svg',NULL,'premium',52.50,43.50,37,'disponible'),(9,2,'Balón de Gas 45 KG Industrial','Costagas','productos/placeholder_9.svg',NULL,'estandar',198.00,188.00,8,'disponible'),(10,3,'Balón de Gas 10 KG','Limagas','productos/placeholder_10.svg',NULL,'estandar',50.00,41.00,94,'disponible'),(11,3,'Balón de Gas 10 KG','Limagas','productos/placeholder_11.svg',NULL,'premium',53.00,44.00,48,'disponible'),(12,3,'Balón de Gas 15 KG','Limagas','productos/placeholder_12.svg',NULL,'estandar',75.00,66.00,18,'disponible'),(13,3,'Balón de Gas 45 KG Industrial','Limagas','productos/placeholder_13.svg',NULL,'estandar',202.00,192.00,10,'disponible'),(14,4,'Balón de Gas 10 KG','Multigas','productos/placeholder_14.svg',NULL,'estandar',48.00,39.00,60,'disponible'),(15,4,'Balón de Gas 45 KG Industrial','Multigas','productos/placeholder_15.svg',NULL,'estandar',195.00,185.00,6,'disponible'),(16,5,'Válvula Reguladora Normal','Surge','productos/placeholder_16.svg',NULL,'estandar',22.00,16.00,44,'disponible'),(17,5,'Válvula Reguladora Premium','Surge','productos/placeholder_17.svg',NULL,'premium',35.00,28.00,29,'disponible'),(18,1,'Válvula Reguladora Normal Original','Solgas','productos/placeholder_18.svg',NULL,'estandar',28.00,22.00,38,'disponible'),(19,1,'Válvula de Acople Rápido Premium','Solgas','productos/placeholder_19.svg',NULL,'premium',42.00,35.00,24,'disponible'),(20,5,'Manguera Cocina Doméstica 1 Metro','Surge','productos/placeholder_20.svg',NULL,'ninguna',8.00,3.50,99,'disponible'),(21,5,'Manguera Cocina Doméstica 2 Metros','Surge','productos/placeholder_21.svg',NULL,'ninguna',14.00,8.00,80,'disponible'),(22,5,'Manguera Cocina Doméstica 3 Metros','Surge','productos/placeholder_22.svg',NULL,'ninguna',19.00,12.00,49,'disponible'),(23,5,'Manguera Alta Presión Industrial 2 Metros','Surge','productos/placeholder_23.svg',NULL,'ninguna',28.00,21.00,29,'disponible'),(24,5,'Abrazadera Metálica Reforzada (Par)','Surge','productos/placeholder_24.svg',NULL,'ninguna',1.50,0.50,249,'disponible'),(25,5,'Pack Básico (Válvula Normal + Manguera 1.5M + Abrazaderas)','Surge','productos/placeholder_25.svg',NULL,'estandar',32.00,23.00,20,'disponible'),(26,1,'Pack Kit Seguridad (Válvula Premium Solgas + Manguera 2M + Abrazaderas)','Solgas','productos/placeholder_26.svg',NULL,'premium',58.00,48.00,14,'disponible');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre_empresa` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `ruc` varchar(11) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_contacto` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'Solgas S.A.','20100123456','988111222','Marco Antonio Solís','activo'),(2,'Costagas S.A.C.','20200234567','988222333','Fiorella Retamozo','activo'),(3,'Limagas S.A.','20300345678','988333444','Carlos Neuhaus','activo'),(4,'Multigas Perú E.I.R.L.','20400456789','988444555','Pedro Castillo Gómez','activo'),(5,'Surge Industrial S.A.C.','20500567890','988555666','Roberto Palacios','activo');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reabastecimientos`
--

DROP TABLE IF EXISTS `reabastecimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reabastecimientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proveedor_id` bigint unsigned NOT NULL,
  `usuario_id` bigint unsigned NOT NULL COMMENT 'Administrador que recibe el camión',
  `monto_total_compra` decimal(10,2) NOT NULL,
  `fecha_compra` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reabastecimientos_proveedor` (`proveedor_id`),
  KEY `fk_reabastecimientos_usuario` (`usuario_id`),
  CONSTRAINT `fk_reabastecimientos_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_reabastecimientos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reabastecimientos`
--

LOCK TABLES `reabastecimientos` WRITE;
/*!40000 ALTER TABLE `reabastecimientos` DISABLE KEYS */;
INSERT INTO `reabastecimientos` VALUES (1,2,1,486.00,'2026-07-10'),(2,2,1,96.00,'2026-07-12');
/*!40000 ALTER TABLE `reabastecimientos` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('1zNU4EgMelidWCkioDYQQ2AsozwZ0ATu147XqyKo',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJkMnVlUG5SRGR6cUVUWDhrc2lUWG5IZXdGWE03b1RQZnVTTG9kbGZGIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3NlZ3VpbWllbnRvXC9jb25zdWx0YXI/Y29kaWdvPUFORy03NDA1Iiwicm91dGUiOiJjbGllbnRlLnNlZ3VpbWllbnRvLmNvbnN1bHRhciJ9fQ==',1784000922),('bYGquYH06ZFRyC5ZbTMBT5ENz1Xn4T8FEy1jfXJB',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','eyJfdG9rZW4iOiIxWlRGWnI5dG5yRUtGVVdkYWpRczl4RzB5UzlWZkc4ZG5BNHVIOUxvIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9rcGlzIiwicm91dGUiOiJhZG1pbi5rcGlzIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1783940455),('UtAd86J08Ht1Qo93uB1ixuGYW8gcN7mQehoVNZiP',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI5a3BaNEpxU1Z6M1pFVGdjQW1pWDZGNzVMTDZYQUFabmVNY0FEb1lOIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3JlY2VwY2lvbmlzdGFcL2Rhc2hib2FyZCIsInJvdXRlIjoicmVjZXBjaW9uaXN0YS5kYXNoYm9hcmQifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9',1783924550);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_pago`
--

DROP TABLE IF EXISTS `tipos_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_pago` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_pago`
--

LOCK TABLES `tipos_pago` WRITE;
/*!40000 ALTER TABLE `tipos_pago` DISABLE KEYS */;
INSERT INTO `tipos_pago` VALUES (1,'Efectivo','activo'),(2,'Tarjeta Visa / Mastercard','activo'),(3,'Yape','activo'),(4,'Plin','activo');
/*!40000 ALTER TABLE `tipos_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documento_identidad` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `rol` enum('administrador','recepcionista','motorizado') COLLATE utf8mb4_spanish_ci NOT NULL,
  `estado` enum('activo','inactivo','suspendido') COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_correo` (`correo`),
  UNIQUE KEY `uk_usuarios_documento` (`documento_identidad`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'70123456','Diego Alonso Alvarado','administrador@gmail.com','$2y$12$vBIoGbZH0h1VTVFN3GcwseNHZv9Z7d9UriszOzqBztT2b1bS0v4ye','987654321','administrador','activo'),(2,'45678912','Ana Rosa Martínez','recepcionista@gmail.com','$2y$12$vBIoGbZH0h1VTVFN3GcwseNHZv9Z7d9UriszOzqBztT2b1bS0v4ye','912345678','recepcionista','activo'),(3,'12345678','Carlos Walter García','motorizado@gmail.com','$2y$12$vBIoGbZH0h1VTVFN3GcwseNHZv9Z7d9UriszOzqBztT2b1bS0v4ye','998877665','motorizado','activo'),(4,'23456789','Juan Alberto Mendoza','motorizado11@gmail.com','$2y$12$vBIoGbZH0h1VTVFN3GcwseNHZv9Z7d9UriszOzqBztT2b1bS0v4ye','994455661','motorizado','activo'),(5,'34567890','Luis Fernando Torres','motorizado2@gmail.com','$2y$12$vBIoGbZH0h1VTVFN3GcwseNHZv9Z7d9UriszOzqBztT2b1bS0v4ye','994455662','motorizado','activo');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-19 20:30:11
