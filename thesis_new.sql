
DROP TABLE IF EXISTS `accomplishment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accomplishment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client` varchar(255) DEFAULT NULL,
  `scope_work` varchar(255) DEFAULT NULL,
  `date_completed` datetime DEFAULT NULL,
  `project_cost` int DEFAULT NULL,
  `materials` int DEFAULT NULL,
  `payroll` int DEFAULT NULL,
  `gross` int DEFAULT NULL,
  `expenses` int DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status_check` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `deductions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deductions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(255) DEFAULT NULL,
  `name` varchar(220) DEFAULT NULL,
  `absent` int DEFAULT NULL,
  `late` int DEFAULT NULL,
  `allowance` int DEFAULT NULL,
  `cash_advance` int DEFAULT NULL,
  `sss` int DEFAULT NULL,
  `philhealth` int DEFAULT NULL,
  `tax` int DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `birthday` datetime DEFAULT NULL,
  `phone` int NOT NULL,
  `address` varchar(255) NOT NULL,
  `salary` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `payroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `total_work` int DEFAULT NULL,
  `late` int DEFAULT NULL,
  `absent` int DEFAULT NULL,
  `cash_advance` int DEFAULT NULL,
  `deduction` int DEFAULT NULL,
  `net_pay` int DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `halfday` int DEFAULT NULL,
  `date_inserted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `payroll_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `total_work` varchar(50) DEFAULT NULL,
  `late` int DEFAULT NULL,
  `absent` int DEFAULT NULL,
  `cash_advance` int DEFAULT NULL,
  `deduction` int DEFAULT NULL,
  `net_pay` int DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `halfday` varchar(255) DEFAULT NULL,
  `date_inserted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `priv` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
