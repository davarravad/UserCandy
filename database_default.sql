-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 01, 2025 at 12:11 AM
-- Server version: 8.0.42-0ubuntu0.22.04.1
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uc_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `uc_activitylog`
--

CREATE TABLE `uc_activitylog` (
  `id` int NOT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additionalinfo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `ip` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_attempts`
--

CREATE TABLE `uc_attempts` (
  `ip` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count` int DEFAULT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_helper_comments`
--

CREATE TABLE `uc_helper_comments` (
  `id` int NOT NULL,
  `com_id` int DEFAULT NULL,
  `com_sec_id` int DEFAULT NULL,
  `com_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `com_user_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `com_server` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `com_uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `com_owner_userid` int DEFAULT NULL,
  `com_content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_roles`
--

CREATE TABLE `uc_roles` (
  `id` int NOT NULL,
  `roleName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleDescription` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleColor` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `updateTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `createTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uc_roles`
--

INSERT INTO `uc_roles` (`id`, `roleName`, `roleDescription`, `roleColor`, `updateTimestamp`, `createTimestamp`) VALUES
(1, 'Admin', 'Administrator', '#00ff00', '2022-11-08 00:34:06', '2022-11-08 00:34:06'),
(2, 'User', 'Default User', '#0000ff', '2022-11-08 00:35:16', '2022-11-08 00:34:51'),
(3, 'Planner', 'Planner Role', '#0074ad', '2022-11-10 02:34:39', '2022-11-09 05:31:33'),
(4, 'CSR', 'CSR Role', '#00b0c7', '2022-11-10 02:34:43', '2022-11-09 05:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `uc_sessions`
--

CREATE TABLE `uc_sessions` (
  `id` int NOT NULL,
  `uid` int DEFAULT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiredate` datetime DEFAULT NULL,
  `ip` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uc_sessions`
--

INSERT INTO `uc_sessions` (`id`, `uid`, `username`, `hash`, `expiredate`, `ip`) VALUES
(8, 1, 'david.sargent@ventureexpress.c', 'a4479b9060187895f21f91d968736fc5', '2025-05-22 10:10:55', '174.229.21.94'),
(9, 1, 'david.sargent@ventureexpress.c', 'ddd1ae58a65de58a6348a8547a77639b', '2025-05-23 10:16:07', '174.229.21.94'),
(10, 1, 'david.sargent@ventureexpress.c', '2c7a9bcb59aeef417763a9b173ea782a', '2025-05-28 07:31:41', '174.229.21.94'),
(11, 1, 'david.sargent@ventureexpress.c', '3abe833b07c2c65fed8f06e4343b1d48', '2025-05-29 14:17:48', '174.229.21.94'),
(12, 1, 'david.sargent@ventureexpress.c', '43bbc88bce6fdba92846f84555b3c73c', '2025-06-03 09:19:41', '174.229.21.94'),
(13, 1, 'david.sargent@ventureexpress.c', '71ca017c43828b57dd66489d3a3c23f9', '2025-06-04 10:35:55', '174.229.21.94'),
(14, 1, 'david.sargent@ventureexpress.c', '27b44f39b46b5be0a9e04836150b8e9f', '2025-06-05 13:23:33', '174.229.21.94'),
(15, 1, 'david.sargent@ventureexpress.c', '97a4afedece267251b805214c84b76e3', '2025-06-07 08:53:03', '174.229.21.94'),
(16, 1, 'david.sargent@ventureexpress.c', 'd5d05c0aec078ed422bb36d5b9dd8030', '2025-06-10 08:07:43', '174.229.21.94'),
(17, 1, 'david.sargent@ventureexpress.c', '1a8040b75d758af09e9da528792de9b5', '2025-06-10 09:30:21', '174.229.21.94'),
(18, 1, 'david.sargent@ventureexpress.c', '141545335281775e22cc4ac5a0c46188', '2025-06-10 10:00:17', '174.229.21.94'),
(19, 1, 'david.sargent@ventureexpress.c', '0a1533e8970f06a6e44fc265c1828dfe', '2025-06-10 18:20:55', '173.31.4.161'),
(20, 1, 'david.sargent@ventureexpress.c', '626bc9d26d31b9edaf3fff5b71a6cc4e', '2025-06-10 18:52:34', '173.31.4.161'),
(21, 1, 'david.sargent@ventureexpress.c', 'd580bcbfc82a1b78df42b1d9e777e2ea', '2025-06-11 08:05:19', '174.229.21.94'),
(22, 1, 'david.sargent@ventureexpress.c', 'e872bc3986e26ecb3418c8a8918abac2', '2025-06-18 07:18:29', '174.229.21.181'),
(23, 1, 'david.sargent@ventureexpress.c', '4880b0c2c67354dc4fea2d89da98cb7f', '2025-06-20 07:18:13', '174.229.21.181'),
(24, 1, 'david.sargent@ventureexpress.c', '50eaa22459b40caf39977831ba5e53e5', '2025-06-21 11:58:12', '174.229.21.181'),
(25, 1, 'david.sargent@ventureexpress.c', '5683a743e74a5114a08958dd70dd3978', '2025-06-24 07:24:04', '174.229.21.181'),
(26, 1, 'david.sargent@ventureexpress.c', '55c0ba2effef83851e0fd964b38cc9bb', '2025-06-25 10:04:43', '174.229.21.181'),
(27, 1, 'david.sargent@ventureexpress.c', 'debc3cd321a63214b94eab8deeb67fa8', '2025-06-26 12:58:31', '174.229.21.181'),
(28, 1, 'david.sargent@ventureexpress.c', '654277de6adfa3ee3b3ec0812c453d9f', '2025-06-28 06:53:05', '174.229.21.181'),
(29, 1, 'david.sargent@ventureexpress.c', '298b0a881338b506e729c11c2d682f80', '2025-07-01 13:18:42', '174.218.49.208');

-- --------------------------------------------------------

--
-- Table structure for table `uc_settings`
--

CREATE TABLE `uc_settings` (
  `setting_id` int NOT NULL,
  `setting_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setting_data` mediumint NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_users`
--

CREATE TABLE `uc_users` (
  `userId` int NOT NULL,
  `userName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userDiscordId` bigint NOT NULL,
  `userAvatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userEmail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userDiscriminator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userLocale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userFirstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userLastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userPassword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assignTrailers` tinyint(1) NOT NULL DEFAULT '0',
  `userToken` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userActive` int DEFAULT '0',
  `userVerified` tinyint(1) NOT NULL DEFAULT '0',
  `userVerifiedBy` int DEFAULT NULL,
  `userVerifiedTimestamp` timestamp NULL DEFAULT NULL,
  `signupTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastLoginTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aboutme` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `signature` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activekey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resetkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `darkMode` tinyint(1) NOT NULL DEFAULT '0',
  `pass_change_timestamp` timestamp NULL DEFAULT NULL,
  `LastLogin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_usersRoles`
--

CREATE TABLE `uc_usersRoles` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `roleId` int NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_users_devices`
--

CREATE TABLE `uc_users_devices` (
  `id` int NOT NULL,
  `userID` int DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new` int NOT NULL DEFAULT '1',
  `useragent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allow` int NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_users_images`
--

CREATE TABLE `uc_users_images` (
  `id` int NOT NULL,
  `userID` int DEFAULT NULL,
  `userImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defaultImage` int NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_users_online`
--

CREATE TABLE `uc_users_online` (
  `id` int NOT NULL,
  `userId` int DEFAULT NULL,
  `lastAccess` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uc_activitylog`
--
ALTER TABLE `uc_activitylog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_helper_comments`
--
ALTER TABLE `uc_helper_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_roles`
--
ALTER TABLE `uc_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupName` (`roleName`);

--
-- Indexes for table `uc_sessions`
--
ALTER TABLE `uc_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_settings`
--
ALTER TABLE `uc_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `uc_users`
--
ALTER TABLE `uc_users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- Indexes for table `uc_usersRoles`
--
ALTER TABLE `uc_usersRoles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`,`roleId`);

--
-- Indexes for table `uc_users_devices`
--
ALTER TABLE `uc_users_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_users_images`
--
ALTER TABLE `uc_users_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_users_online`
--
ALTER TABLE `uc_users_online`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lastAccess` (`lastAccess`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `uc_activitylog`
--
ALTER TABLE `uc_activitylog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uc_helper_comments`
--
ALTER TABLE `uc_helper_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uc_roles`
--
ALTER TABLE `uc_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uc_sessions`
--
ALTER TABLE `uc_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `uc_settings`
--
ALTER TABLE `uc_settings`
  MODIFY `setting_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uc_users`
--
ALTER TABLE `uc_users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uc_usersRoles`
--
ALTER TABLE `uc_usersRoles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uc_users_devices`
--
ALTER TABLE `uc_users_devices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uc_users_images`
--
ALTER TABLE `uc_users_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uc_users_online`
--
ALTER TABLE `uc_users_online`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
