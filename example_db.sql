-- MySQL dump 10.13  Distrib 8.4.6, for Linux (x86_64)
--
-- Host: localhost    Database: example_db
-- ------------------------------------------------------
-- Server version	8.4.6

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
-- Table structure for table `board_posts`
--

DROP TABLE IF EXISTS `board_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `pic_name1` text COLLATE utf8mb4_unicode_ci,
  `pic_name2` text COLLATE utf8mb4_unicode_ci,
  `pic_name3` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `board_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `board_posts`
--

LOCK TABLES `board_posts` WRITE;
/*!40000 ALTER TABLE `board_posts` DISABLE KEYS */;
INSERT INTO `board_posts` VALUES (13,17,'テスト投稿（画像投稿）です。','2026-01-28 09:32:41','176956036169fad12f3d3d9aa86a8f.png',NULL,NULL),(14,18,'テスト投稿(画像なし投稿)です','2026-01-28 09:36:41',NULL,NULL,NULL),(15,17,'テスト投稿（複数枚画像投稿)です。','2026-01-28 09:53:24','1769561604860ef520e1c33c1deee7.png','17695616041b64ae02e5cd041d4946.png',NULL);
/*!40000 ALTER TABLE `board_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `followings`
--

DROP TABLE IF EXISTS `followings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `followings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `follow_id` int unsigned NOT NULL,
  `follower_id` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_follow` (`follow_id`,`follower_id`),
  KEY `follower_id` (`follower_id`),
  CONSTRAINT `followings_ibfk_1` FOREIGN KEY (`follow_id`) REFERENCES `users` (`id`),
  CONSTRAINT `followings_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `followings`
--

LOCK TABLES `followings` WRITE;
/*!40000 ALTER TABLE `followings` DISABLE KEYS */;
INSERT INTO `followings` VALUES (24,18,17,'2026-01-28 09:54:04');
/*!40000 ALTER TABLE `followings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `img_name` text COLLATE utf8mb4_unicode_ci,
  `introd` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (17,'テストユーザー1','test1111@gmail.com','$2y$12$FA/vp.1hshG5WUDg/X83OeznjsDok7xPeDdXbMEPI3KdKx/sAOE/K','2026-01-28 09:31:48','1769560308fafb893b5a98ef8bb9c9e7654db05152e06bd6fc3cf01ebee4.png','こんにちは、テストユーザー1です。'),(18,'テストユーザー2','test2222@gmail.com','$2y$12$gvmrRuqK.mNM/ys8pfRoLOaxcYJxZftizqTQQV69WPRNJ9x1HZSn.','2026-01-28 09:35:47',NULL,'こんにちは、テストユーザー2です。');
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

-- Dump completed on 2026-01-28 13:59:57
