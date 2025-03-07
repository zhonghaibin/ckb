/*
 Navicat Premium Data Transfer

 Source Server         : phpstudy
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : ckb

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 07/03/2025 18:36:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `admin_id` int(11) NOT NULL COMMENT '管理员id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_admin_id`(`role_id`, `admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES (1, 1, 1);
INSERT INTO `admin_roles` VALUES (2, 2, 2);

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `nickname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '昵称',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '/app/admin/avatar.png' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `mobile` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `login_at` datetime NULL DEFAULT NULL COMMENT '登录时间',
  `status` tinyint(4) NULL DEFAULT NULL COMMENT '禁用',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (1, 'superadmin', '超级管理员', '$2y$10$TVMTYImaeleeoSEbEcDN/eHHgv4lMNCJdMD0uOF.EbVYUeA5b1A.y', '/app/admin/avatar.png', NULL, NULL, '2025-03-05 09:49:24', '2025-03-07 15:09:06', '2025-03-07 15:09:06', NULL);
INSERT INTO `admins` VALUES (2, 'admin', 'admin', '$2y$10$Ax5DCAi.mn0folyU9OXzGuymieZtzfKoErVZ4Z0hoWz.AJVjKlUQ6', '/app/admin/avatar.png', '', '', '2025-03-06 16:24:29', '2025-03-07 18:00:56', '2025-03-07 18:00:56', NULL);

-- ----------------------------
-- Table structure for assets
-- ----------------------------
DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(15, 6) UNSIGNED NULL DEFAULT 0.000000 COMMENT '余额',
  `bonus` decimal(15, 6) UNSIGNED NULL DEFAULT 0.000000 COMMENT '收益金额',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `inx_Fields`(`id`, `user_id`, `coin`, `amount`, `bonus`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家钱包' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of assets
-- ----------------------------
INSERT INTO `assets` VALUES (1, 1, 'USDT', 2.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-07 13:49:53');
INSERT INTO `assets` VALUES (2, 1, 'ONE', 0.450000, 0.450000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (3, 1, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (4, 2, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (5, 2, 'ONE', 0.390000, 0.390000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (6, 2, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (7, 3, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (8, 3, 'ONE', 0.320000, 0.320000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (9, 3, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (10, 4, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (11, 4, 'ONE', 0.260000, 0.260000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (12, 4, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (13, 5, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (14, 5, 'ONE', 0.190000, 0.190000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (15, 5, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (16, 6, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (17, 6, 'ONE', 0.130000, 0.130000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (18, 6, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (19, 7, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (20, 7, 'ONE', 0.100000, 0.100000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (21, 7, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (22, 8, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (23, 8, 'ONE', 0.080000, 0.080000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (24, 8, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (25, 9, 'USDT', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (26, 9, 'ONE', 0.310000, 0.310000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (27, 9, 'CBK', 0.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `assets` VALUES (28, 10, 'USDT', 99.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-07 14:35:14');
INSERT INTO `assets` VALUES (29, 10, 'ONE', 1.290000, 1.290000, '2025-03-05 14:10:30', '2025-03-05 14:15:54');
INSERT INTO `assets` VALUES (30, 10, 'CBK', 400.000000, 0.000000, '2025-03-05 14:10:30', '2025-03-07 11:58:38');

-- ----------------------------
-- Table structure for assets_logs
-- ----------------------------
DROP TABLE IF EXISTS `assets_logs`;
CREATE TABLE `assets_logs`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(10, 6) NULL DEFAULT 0.000000,
  `rate` decimal(10, 6) NULL DEFAULT 0.000000,
  `transaction_id` int(11) NULL DEFAULT 0,
  `transaction_log_id` int(11) NULL DEFAULT 0,
  `exchange_id` int(11) NULL DEFAULT 0,
  `recharge_id` int(11) NULL DEFAULT 0,
  `withdraw_id` int(11) NULL DEFAULT 0,
  `type` tinyint(1) NULL DEFAULT 0,
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`id`, `user_id`, `coin`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '钱包变动记录表(账户发生变化时产生)' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of assets_logs
-- ----------------------------
INSERT INTO `assets_logs` VALUES (1, 10, 'ONE', -500.000000, 0.000000, 1, 0, 0, NULL, NULL, 4, '下单', 1741155354, '2025-03-05 14:15:54', '2025-03-05 14:15:54');
INSERT INTO `assets_logs` VALUES (2, 10, 'ONE', 1.290000, 0.080000, 1, 1, 0, NULL, NULL, 6, '每日收益', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (3, 9, 'ONE', 0.260000, 0.200000, 1, 1, 0, NULL, NULL, 7, '分享奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (4, 9, 'ONE', 0.050000, 0.040000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (5, 8, 'ONE', 0.080000, 0.060000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (6, 7, 'ONE', 0.100000, 0.080000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (7, 6, 'ONE', 0.130000, 0.100000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (8, 5, 'ONE', 0.190000, 0.150000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (9, 4, 'ONE', 0.260000, 0.200000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (10, 3, 'ONE', 0.320000, 0.250000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (11, 2, 'ONE', 0.390000, 0.300000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (12, 1, 'ONE', 0.390000, 0.300000, 1, 1, 0, NULL, NULL, 8, '极差奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (13, 1, 'ONE', 0.060000, 0.050000, 1, 1, 0, NULL, NULL, 9, '平级奖', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');
INSERT INTO `assets_logs` VALUES (14, 10, 'CBK', -100.000000, 1.000000, 0, 0, 1, NULL, NULL, 3, '兑换', 1741319918, '2025-03-07 11:58:38', '2025-03-07 11:58:38');
INSERT INTO `assets_logs` VALUES (15, 10, 'USDT', 100.000000, 1.000000, 0, 0, 1, NULL, NULL, 3, '兑换', 1741319918, '2025-03-07 11:58:38', '2025-03-07 11:58:38');
INSERT INTO `assets_logs` VALUES (16, 1, 'USDT', 1.000000, 0.000000, 0, 0, 0, NULL, 0, 1, '充值', 1741326516, '2025-03-07 13:48:36', '2025-03-07 13:48:36');
INSERT INTO `assets_logs` VALUES (17, 1, 'USDT', 1.000000, 0.000000, 0, 0, 0, 2, 0, 1, '充值', 1741326593, '2025-03-07 13:49:53', '2025-03-07 13:49:53');
INSERT INTO `assets_logs` VALUES (18, 10, 'USDT', -1.000000, 0.000000, 0, 0, 0, 0, 1, 2, '提现', 1741329314, '2025-03-07 14:35:14', '2025-03-07 14:35:14');

-- ----------------------------
-- Table structure for banners
-- ----------------------------
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '通告内容' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of banners
-- ----------------------------
INSERT INTO `banners` VALUES (1, '标题1', '/images/avatars/avatar1.png', 1, '2025-03-06 17:20:48', '2025-03-06 17:20:50');
INSERT INTO `banners` VALUES (3, '75', '', 0, '2025-03-07 17:05:31', '2025-03-07 17:05:31');

-- ----------------------------
-- Table structure for exchanges
-- ----------------------------
DROP TABLE IF EXISTS `exchanges`;
CREATE TABLE `exchanges`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `from_coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `to_coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `from_amount` decimal(10, 6) NULL DEFAULT 0.000000,
  `to_amount` decimal(10, 6) NULL DEFAULT 0.000000 COMMENT '兑换后的金额',
  `rate` decimal(10, 6) NULL DEFAULT 0.000000 COMMENT '兑换业务',
  `fee` decimal(10, 6) NULL DEFAULT 0.000000 COMMENT '手续费',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '1兑换成功',
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`id`, `user_id`, `from_coin`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '兑换' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of exchanges
-- ----------------------------
INSERT INTO `exchanges` VALUES (1, 10, 'CBK', 'USDT', 100.000000, 100.000000, 1.000000, 0.000000, 1, 1741319918, '2025-03-07 11:58:38', '2025-03-07 11:58:38');

-- ----------------------------
-- Table structure for login_logs
-- ----------------------------
DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE `login_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '登录日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of login_logs
-- ----------------------------
INSERT INTO `login_logs` VALUES (1, 10, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741161262, '2025-03-05 15:54:22', '2025-03-05 15:54:22');
INSERT INTO `login_logs` VALUES (2, 1, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741162637, '2025-03-05 16:17:17', '2025-03-05 16:17:17');
INSERT INTO `login_logs` VALUES (3, 9, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741162657, '2025-03-05 16:17:37', '2025-03-05 16:17:37');
INSERT INTO `login_logs` VALUES (4, 1, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741164095, '2025-03-05 16:41:35', '2025-03-05 16:41:35');
INSERT INTO `login_logs` VALUES (5, 1, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741253602, '2025-03-06 17:33:22', '2025-03-06 17:33:22');
INSERT INTO `login_logs` VALUES (6, 1, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741257845, '2025-03-06 18:44:05', '2025-03-06 18:44:05');
INSERT INTO `login_logs` VALUES (7, 1, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741261849, '2025-03-06 19:50:49', '2025-03-06 19:50:49');
INSERT INTO `login_logs` VALUES (8, 10, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741262216, '2025-03-06 19:56:56', '2025-03-06 19:56:56');
INSERT INTO `login_logs` VALUES (9, 10, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741319323, '2025-03-07 11:48:43', '2025-03-07 11:48:43');
INSERT INTO `login_logs` VALUES (10, 10, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741328118, '2025-03-07 14:15:18', '2025-03-07 14:15:18');
INSERT INTO `login_logs` VALUES (11, 10, 'Apifox/1.0.0 (https://apifox.com)', '127.0.0.1', 1741328169, '2025-03-07 14:16:09', '2025-03-07 14:16:09');

-- ----------------------------
-- Table structure for notices
-- ----------------------------
DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '通告内容' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of notices
-- ----------------------------
INSERT INTO `notices` VALUES (1, '重要通知', '<p>6664565464566</p>', 1, '2025-03-06 17:23:19', '2025-03-07 17:52:53');
INSERT INTO `notices` VALUES (2, '65', '<p>555</p>', 0, '2025-03-07 16:56:24', '2025-03-07 17:59:29');
INSERT INTO `notices` VALUES (3, '676767', '', 1, '2025-03-07 16:57:23', '2025-03-07 17:59:24');
INSERT INTO `notices` VALUES (4, '4234', '', 0, '2025-03-07 16:58:42', '2025-03-07 16:58:42');
INSERT INTO `notices` VALUES (5, '7567', NULL, 0, '2025-03-07 17:06:03', '2025-03-07 17:06:03');
INSERT INTO `notices` VALUES (6, '66', NULL, 0, '2025-03-07 17:08:40', '2025-03-07 17:08:40');
INSERT INTO `notices` VALUES (7, '76', NULL, 0, '2025-03-07 17:09:35', '2025-03-07 17:09:35');
INSERT INTO `notices` VALUES (8, '657', NULL, 0, '2025-03-07 17:10:41', '2025-03-07 17:10:41');
INSERT INTO `notices` VALUES (9, '645', '<p>45</p>', 0, '2025-03-07 17:34:21', '2025-03-07 17:34:21');
INSERT INTO `notices` VALUES (10, '6546', '<p>654645645</p>', 0, '2025-03-07 17:51:16', '2025-03-07 17:51:16');

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '键',
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '值',
  `created_at` datetime NOT NULL DEFAULT '2022-08-15 00:00:00' COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT '2022-08-15 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '选项表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `options` VALUES (1, 'system_config', '{\"logo\":{\"title\":\"\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"image\":\"\\/app\\/admin\\/admin\\/images\\/logo.png\",\"icp\":\"\",\"beian\":\"\",\"footer_txt\":\"\"},\"menu\":{\"data\":\"\\/app\\/admin\\/rule\\/get\",\"method\":\"GET\",\"accordion\":true,\"collapse\":false,\"control\":false,\"controlWidth\":500,\"select\":\"0\",\"async\":true},\"tab\":{\"enable\":true,\"keepState\":true,\"preload\":false,\"session\":true,\"max\":\"30\",\"index\":{\"id\":\"0\",\"href\":\"\\/app\\/admin\\/index\\/dashboard\",\"title\":\"\\u4eea\\u8868\\u76d8\"}},\"theme\":{\"defaultColor\":\"2\",\"defaultMenu\":\"light-theme\",\"defaultHeader\":\"light-theme\",\"allowCustom\":true,\"banner\":false},\"colors\":[{\"id\":\"1\",\"color\":\"#36b368\",\"second\":\"#f0f9eb\"},{\"id\":\"2\",\"color\":\"#2d8cf0\",\"second\":\"#ecf5ff\"},{\"id\":\"3\",\"color\":\"#f6ad55\",\"second\":\"#fdf6ec\"},{\"id\":\"4\",\"color\":\"#f56c6c\",\"second\":\"#fef0f0\"},{\"id\":\"5\",\"color\":\"#3963bc\",\"second\":\"#ecf5ff\"}],\"other\":{\"keepLoad\":\"500\",\"autoHead\":false,\"footer\":false},\"header\":{\"message\":false}}', '2022-12-05 14:49:01', '2025-03-07 13:29:40');
INSERT INTO `options` VALUES (2, 'table_form_schema_wa_users', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false},\"username\":{\"field\":\"username\",\"_field_id\":\"1\",\"comment\":\"用户名\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"nickname\":{\"field\":\"nickname\",\"_field_id\":\"2\",\"comment\":\"昵称\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"password\":{\"field\":\"password\",\"_field_id\":\"3\",\"comment\":\"密码\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"sex\":{\"field\":\"sex\",\"_field_id\":\"4\",\"comment\":\"性别\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/dict\\/get\\/sex\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"avatar\":{\"field\":\"avatar\",\"_field_id\":\"5\",\"comment\":\"头像\",\"control\":\"uploadImage\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/avatar\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"email\":{\"field\":\"email\",\"_field_id\":\"6\",\"comment\":\"邮箱\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"mobile\":{\"field\":\"mobile\",\"_field_id\":\"7\",\"comment\":\"手机\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"level\":{\"field\":\"level\",\"_field_id\":\"8\",\"comment\":\"等级\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"birthday\":{\"field\":\"birthday\",\"_field_id\":\"9\",\"comment\":\"生日\",\"control\":\"datePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"money\":{\"field\":\"money\",\"_field_id\":\"10\",\"comment\":\"余额(元)\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"score\":{\"field\":\"score\",\"_field_id\":\"11\",\"comment\":\"积分\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"last_time\":{\"field\":\"last_time\",\"_field_id\":\"12\",\"comment\":\"登录时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"last_ip\":{\"field\":\"last_ip\",\"_field_id\":\"13\",\"comment\":\"登录ip\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"join_time\":{\"field\":\"join_time\",\"_field_id\":\"14\",\"comment\":\"注册时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"join_ip\":{\"field\":\"join_ip\",\"_field_id\":\"15\",\"comment\":\"注册ip\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"token\":{\"field\":\"token\",\"_field_id\":\"16\",\"comment\":\"token\",\"control\":\"input\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"17\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"18\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"between\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"role\":{\"field\":\"role\",\"_field_id\":\"19\",\"comment\":\"角色\",\"control\":\"inputNumber\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"status\":{\"field\":\"status\",\"_field_id\":\"20\",\"comment\":\"禁用\",\"control\":\"switch\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-23 15:28:13');
INSERT INTO `options` VALUES (3, 'table_form_schema_wa_roles', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"角色组\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"rules\":{\"field\":\"rules\",\"_field_id\":\"2\",\"comment\":\"权限\",\"control\":\"treeSelectMulti\",\"control_args\":\"url:\\/app\\/admin\\/rule\\/get?type=0,1,2\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"3\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"4\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"pid\":{\"field\":\"pid\",\"_field_id\":\"5\",\"comment\":\"父级\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/role\\/select?format=tree\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-19 14:24:25');
INSERT INTO `options` VALUES (4, 'table_form_schema_wa_rules', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"title\":{\"field\":\"title\",\"_field_id\":\"1\",\"comment\":\"标题\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"icon\":{\"field\":\"icon\",\"_field_id\":\"2\",\"comment\":\"图标\",\"control\":\"iconPicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"key\":{\"field\":\"key\",\"_field_id\":\"3\",\"comment\":\"标识\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"pid\":{\"field\":\"pid\",\"_field_id\":\"4\",\"comment\":\"上级菜单\",\"control\":\"treeSelect\",\"control_args\":\"\\/app\\/admin\\/rule\\/select?format=tree&type=0,1\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"5\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"6\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"href\":{\"field\":\"href\",\"_field_id\":\"7\",\"comment\":\"url\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"type\":{\"field\":\"type\",\"_field_id\":\"8\",\"comment\":\"类型\",\"control\":\"select\",\"control_args\":\"data:0:目录,1:菜单,2:权限\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"weight\":{\"field\":\"weight\",\"_field_id\":\"9\",\"comment\":\"排序\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:44:45');
INSERT INTO `options` VALUES (5, 'table_form_schema_wa_admins', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"ID\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"search_type\":\"between\",\"form_show\":false,\"searchable\":false},\"username\":{\"field\":\"username\",\"_field_id\":\"1\",\"comment\":\"用户名\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"nickname\":{\"field\":\"nickname\",\"_field_id\":\"2\",\"comment\":\"昵称\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"password\":{\"field\":\"password\",\"_field_id\":\"3\",\"comment\":\"密码\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"avatar\":{\"field\":\"avatar\",\"_field_id\":\"4\",\"comment\":\"头像\",\"control\":\"uploadImage\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/avatar\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"email\":{\"field\":\"email\",\"_field_id\":\"5\",\"comment\":\"邮箱\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"mobile\":{\"field\":\"mobile\",\"_field_id\":\"6\",\"comment\":\"手机\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"7\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"8\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"login_at\":{\"field\":\"login_at\",\"_field_id\":\"9\",\"comment\":\"登录时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"between\",\"enable_sort\":false,\"searchable\":false},\"status\":{\"field\":\"status\",\"_field_id\":\"10\",\"comment\":\"禁用\",\"control\":\"switch\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-23 15:36:48');
INSERT INTO `options` VALUES (6, 'table_form_schema_wa_options', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"键\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"value\":{\"field\":\"value\",\"_field_id\":\"2\",\"comment\":\"值\",\"control\":\"textArea\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"3\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"4\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:36:57');
INSERT INTO `options` VALUES (7, 'table_form_schema_wa_uploads', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"search_type\":\"normal\",\"form_show\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"名称\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"url\":{\"field\":\"url\",\"_field_id\":\"2\",\"comment\":\"文件\",\"control\":\"upload\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/file\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"admin_id\":{\"field\":\"admin_id\",\"_field_id\":\"3\",\"comment\":\"管理员\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/admin\\/select?format=select\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"file_size\":{\"field\":\"file_size\",\"_field_id\":\"4\",\"comment\":\"文件大小\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"between\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"mime_type\":{\"field\":\"mime_type\",\"_field_id\":\"5\",\"comment\":\"mime类型\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_width\":{\"field\":\"image_width\",\"_field_id\":\"6\",\"comment\":\"图片宽度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_height\":{\"field\":\"image_height\",\"_field_id\":\"7\",\"comment\":\"图片高度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"ext\":{\"field\":\"ext\",\"_field_id\":\"8\",\"comment\":\"扩展名\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"storage\":{\"field\":\"storage\",\"_field_id\":\"9\",\"comment\":\"存储位置\",\"control\":\"input\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"10\",\"comment\":\"上传时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"searchable\":true,\"search_type\":\"between\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false},\"category\":{\"field\":\"category\",\"_field_id\":\"11\",\"comment\":\"类别\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/dict\\/get\\/upload\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"12\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:47:45');
INSERT INTO `options` VALUES (8, 'table_form_schema_wa_uploads', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"search_type\":\"normal\",\"form_show\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"名称\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"url\":{\"field\":\"url\",\"_field_id\":\"2\",\"comment\":\"文件\",\"control\":\"upload\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/file\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"admin_id\":{\"field\":\"admin_id\",\"_field_id\":\"3\",\"comment\":\"管理员\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/admin\\/select?format=select\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"file_size\":{\"field\":\"file_size\",\"_field_id\":\"4\",\"comment\":\"文件大小\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"between\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"mime_type\":{\"field\":\"mime_type\",\"_field_id\":\"5\",\"comment\":\"mime类型\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_width\":{\"field\":\"image_width\",\"_field_id\":\"6\",\"comment\":\"图片宽度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_height\":{\"field\":\"image_height\",\"_field_id\":\"7\",\"comment\":\"图片高度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"ext\":{\"field\":\"ext\",\"_field_id\":\"8\",\"comment\":\"扩展名\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"storage\":{\"field\":\"storage\",\"_field_id\":\"9\",\"comment\":\"存储位置\",\"control\":\"input\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"10\",\"comment\":\"上传时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"searchable\":true,\"search_type\":\"between\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false},\"category\":{\"field\":\"category\",\"_field_id\":\"11\",\"comment\":\"类别\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/dict\\/get\\/upload\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"12\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:47:45');
INSERT INTO `options` VALUES (9, 'dict_upload', '[{\"value\":\"1\",\"name\":\"分类1\"},{\"value\":\"2\",\"name\":\"分类2\"},{\"value\":\"3\",\"name\":\"分类3\"}]', '2022-12-04 16:24:13', '2022-12-04 16:24:13');
INSERT INTO `options` VALUES (10, 'dict_sex', '[{\"value\":\"0\",\"name\":\"女\"},{\"value\":\"1\",\"name\":\"男\"}]', '2022-12-04 15:04:40', '2022-12-04 15:04:40');
INSERT INTO `options` VALUES (11, 'dict_status', '[{\"value\":\"0\",\"name\":\"正常\"},{\"value\":\"1\",\"name\":\"禁用\"}]', '2022-12-04 15:05:09', '2022-12-04 15:05:09');
INSERT INTO `options` VALUES (17, 'table_form_schema_wa_admin_roles', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false},\"role_id\":{\"field\":\"role_id\",\"_field_id\":\"1\",\"comment\":\"角色id\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"admin_id\":{\"field\":\"admin_id\",\"_field_id\":\"2\",\"comment\":\"管理员id\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-20 19:42:51');
INSERT INTO `options` VALUES (18, 'dict_dict_name', '[{\"value\":\"dict_name\",\"name\":\"字典名称\"},{\"value\":\"status\",\"name\":\"启禁用状态\"},{\"value\":\"sex\",\"name\":\"性别\"},{\"value\":\"upload\",\"name\":\"附件分类\"}]', '2022-08-15 00:00:00', '2022-12-20 19:42:51');
INSERT INTO `options` VALUES (19, 'config', '{\"base_info\":{\"web_url\":\"66\"}}', '2022-12-05 14:49:01', '2025-03-07 16:41:56');

-- ----------------------------
-- Table structure for recharges
-- ----------------------------
DROP TABLE IF EXISTS `recharges`;
CREATE TABLE `recharges`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(10, 6) NULL DEFAULT NULL,
  `remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `fee` decimal(10, 6) NULL DEFAULT 0.000000 COMMENT '手续费',
  `datetime` int(11) NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家充值记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of recharges
-- ----------------------------
INSERT INTO `recharges` VALUES (1, 1, 'USDT', 1.000000, '1', 1, 0.000000, 1, '2025-03-05 17:03:34', '2025-03-05 17:03:36');
INSERT INTO `recharges` VALUES (2, 1, 'USDT', 1.000000, '', 1, 0.000000, 1741326593, '2025-03-07 13:49:53', '2025-03-07 13:49:53');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色组',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '权限',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '更新时间',
  `pid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '父级',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, '超级管理员', '*', '2022-08-13 16:15:01', '2022-12-23 12:05:07', NULL);
INSERT INTO `roles` VALUES (2, '管理员', '85,86,87,88,116,117,118,119,122,123,124,125,126,121', '2025-03-06 16:23:57', '2025-03-07 18:00:25', 1);

-- ----------------------------
-- Table structure for rules
-- ----------------------------
DROP TABLE IF EXISTS `rules`;
CREATE TABLE `rules`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标识',
  `pid` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '上级菜单',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '更新时间',
  `href` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'url',
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '类型',
  `weight` int(11) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 127 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限规则' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rules
-- ----------------------------
INSERT INTO `rules` VALUES (1, '数据库', 'layui-icon-template-1', 'database', 0, '2025-03-05 09:49:00', '2025-03-05 09:49:00', NULL, 0, 1000);
INSERT INTO `rules` VALUES (2, '所有表', NULL, 'plugin\\admin\\app\\controller\\TableController', 1, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/table/index', 1, 800);
INSERT INTO `rules` VALUES (3, '权限管理', 'layui-icon-vercode', 'auth', 0, '2025-03-05 09:49:00', '2025-03-05 09:49:00', NULL, 0, 900);
INSERT INTO `rules` VALUES (4, '账户管理', NULL, 'plugin\\admin\\app\\controller\\AdminController', 3, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/admin/index', 1, 1000);
INSERT INTO `rules` VALUES (5, '角色管理', NULL, 'plugin\\admin\\app\\controller\\RoleController', 3, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/role/index', 1, 900);
INSERT INTO `rules` VALUES (6, '菜单管理', NULL, 'plugin\\admin\\app\\controller\\RuleController', 3, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/rule/index', 1, 800);
INSERT INTO `rules` VALUES (7, '会员管理', 'layui-icon-username', 'user', 0, '2025-03-05 09:49:00', '2025-03-05 09:49:00', NULL, 0, 800);
INSERT INTO `rules` VALUES (8, '用户', NULL, 'plugin\\admin\\app\\controller\\UserController', 7, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/user/index', 1, 800);
INSERT INTO `rules` VALUES (9, '通用设置', 'layui-icon-set', 'common', 0, '2025-03-05 09:49:00', '2025-03-05 09:49:00', NULL, 0, 700);
INSERT INTO `rules` VALUES (10, '个人资料', NULL, 'plugin\\admin\\app\\controller\\AccountController', 9, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/account/index', 1, 800);
INSERT INTO `rules` VALUES (11, '附件管理', NULL, 'plugin\\admin\\app\\controller\\UploadController', 9, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/upload/index', 1, 700);
INSERT INTO `rules` VALUES (12, '字典设置', NULL, 'plugin\\admin\\app\\controller\\DictController', 9, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/dict/index', 1, 600);
INSERT INTO `rules` VALUES (13, '系统设置', NULL, 'plugin\\admin\\app\\controller\\ConfigController', 9, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/config/index', 1, 500);
INSERT INTO `rules` VALUES (14, '插件管理', 'layui-icon-app', 'plugin', 0, '2025-03-05 09:49:00', '2025-03-05 09:49:00', NULL, 0, 600);
INSERT INTO `rules` VALUES (15, '应用插件', NULL, 'plugin\\admin\\app\\controller\\PluginController', 14, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/plugin/index', 1, 800);
INSERT INTO `rules` VALUES (16, '开发辅助', 'layui-icon-fonts-code', 'dev', 0, '2025-03-05 09:49:00', '2025-03-05 09:49:00', NULL, 0, 500);
INSERT INTO `rules` VALUES (17, '表单构建', NULL, 'plugin\\admin\\app\\controller\\DevController', 16, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/dev/form-build', 1, 800);
INSERT INTO `rules` VALUES (18, '示例页面', 'layui-icon-templeate-1', 'demos', 0, '2025-03-05 09:49:00', '2025-03-05 09:49:00', NULL, 0, 400);
INSERT INTO `rules` VALUES (19, '工作空间', 'layui-icon-console', 'demo1', 18, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '', 0, 0);
INSERT INTO `rules` VALUES (20, '控制后台', 'layui-icon-console', 'demo10', 19, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/console/console1.html', 1, 0);
INSERT INTO `rules` VALUES (21, '数据分析', 'layui-icon-console', 'demo13', 19, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/console/console2.html', 1, 0);
INSERT INTO `rules` VALUES (22, '百度一下', 'layui-icon-console', 'demo14', 19, '2025-03-05 09:49:00', '2025-03-05 09:49:00', 'http://www.baidu.com', 1, 0);
INSERT INTO `rules` VALUES (23, '主题预览', 'layui-icon-console', 'demo15', 19, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/theme.html', 1, 0);
INSERT INTO `rules` VALUES (24, '常用组件', 'layui-icon-component', 'demo20', 18, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '', 0, 0);
INSERT INTO `rules` VALUES (25, '功能按钮', 'layui-icon-face-smile', 'demo2011', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/button.html', 1, 0);
INSERT INTO `rules` VALUES (26, '表单集合', 'layui-icon-face-cry', 'demo2014', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/form.html', 1, 0);
INSERT INTO `rules` VALUES (27, '字体图标', 'layui-icon-face-cry', 'demo2010', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/icon.html', 1, 0);
INSERT INTO `rules` VALUES (28, '多选下拉', 'layui-icon-face-cry', 'demo2012', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/select.html', 1, 0);
INSERT INTO `rules` VALUES (29, '动态标签', 'layui-icon-face-cry', 'demo2013', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/tag.html', 1, 0);
INSERT INTO `rules` VALUES (30, '数据表格', 'layui-icon-face-cry', 'demo2031', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/table.html', 1, 0);
INSERT INTO `rules` VALUES (31, '分布表单', 'layui-icon-face-cry', 'demo2032', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/step.html', 1, 0);
INSERT INTO `rules` VALUES (32, '树形表格', 'layui-icon-face-cry', 'demo2033', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/treetable.html', 1, 0);
INSERT INTO `rules` VALUES (33, '树状结构', 'layui-icon-face-cry', 'demo2034', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/dtree.html', 1, 0);
INSERT INTO `rules` VALUES (34, '文本编辑', 'layui-icon-face-cry', 'demo2035', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/tinymce.html', 1, 0);
INSERT INTO `rules` VALUES (35, '卡片组件', 'layui-icon-face-cry', 'demo2036', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/card.html', 1, 0);
INSERT INTO `rules` VALUES (36, '抽屉组件', 'layui-icon-face-cry', 'demo2021', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/drawer.html', 1, 0);
INSERT INTO `rules` VALUES (37, '消息通知', 'layui-icon-face-cry', 'demo2022', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/notice.html', 1, 0);
INSERT INTO `rules` VALUES (38, '加载组件', 'layui-icon-face-cry', 'demo2024', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/loading.html', 1, 0);
INSERT INTO `rules` VALUES (39, '弹层组件', 'layui-icon-face-cry', 'demo2023', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/popup.html', 1, 0);
INSERT INTO `rules` VALUES (40, '多选项卡', 'layui-icon-face-cry', 'demo60131', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/tab.html', 1, 0);
INSERT INTO `rules` VALUES (41, '数据菜单', 'layui-icon-face-cry', 'demo60132', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/menu.html', 1, 0);
INSERT INTO `rules` VALUES (42, '哈希加密', 'layui-icon-face-cry', 'demo2041', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/encrypt.html', 1, 0);
INSERT INTO `rules` VALUES (43, '图标选择', 'layui-icon-face-cry', 'demo2042', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/iconPicker.html', 1, 0);
INSERT INTO `rules` VALUES (44, '省市级联', 'layui-icon-face-cry', 'demo2043', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/area.html', 1, 0);
INSERT INTO `rules` VALUES (45, '数字滚动', 'layui-icon-face-cry', 'demo2044', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/count.html', 1, 0);
INSERT INTO `rules` VALUES (46, '顶部返回', 'layui-icon-face-cry', 'demo2045', 24, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/document/topBar.html', 1, 0);
INSERT INTO `rules` VALUES (47, '结果页面', 'layui-icon-auz', 'demo666', 18, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '', 0, 0);
INSERT INTO `rules` VALUES (48, '成功', 'layui-icon-face-smile', 'demo667', 47, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/result/success.html', 1, 0);
INSERT INTO `rules` VALUES (49, '失败', 'layui-icon-face-cry', 'demo668', 47, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/result/error.html', 1, 0);
INSERT INTO `rules` VALUES (50, '错误页面', 'layui-icon-face-cry', 'demo-error', 18, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '', 0, 0);
INSERT INTO `rules` VALUES (51, '403', 'layui-icon-face-smile', 'demo403', 50, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/error/403.html', 1, 0);
INSERT INTO `rules` VALUES (52, '404', 'layui-icon-face-cry', 'demo404', 50, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/error/404.html', 1, 0);
INSERT INTO `rules` VALUES (53, '500', 'layui-icon-face-cry', 'demo500', 50, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/error/500.html', 1, 0);
INSERT INTO `rules` VALUES (54, '系统管理', 'layui-icon-set-fill', 'demo-system', 18, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '', 0, 0);
INSERT INTO `rules` VALUES (55, '用户管理', 'layui-icon-face-smile', 'demo601', 54, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/user.html', 1, 0);
INSERT INTO `rules` VALUES (56, '角色管理', 'layui-icon-face-cry', 'demo602', 54, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/role.html', 1, 0);
INSERT INTO `rules` VALUES (57, '权限管理', 'layui-icon-face-cry', 'demo603', 54, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/power.html', 1, 0);
INSERT INTO `rules` VALUES (58, '部门管理', 'layui-icon-face-cry', 'demo604', 54, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/deptment.html', 1, 0);
INSERT INTO `rules` VALUES (59, '行为日志', 'layui-icon-face-cry', 'demo605', 54, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/log.html', 1, 0);
INSERT INTO `rules` VALUES (60, '数据字典', 'layui-icon-face-cry', 'demo606', 54, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/dict.html', 1, 0);
INSERT INTO `rules` VALUES (61, '常用页面', 'layui-icon-template-1', 'demo-common', 18, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '', 0, 0);
INSERT INTO `rules` VALUES (62, '空白页面', 'layui-icon-face-smile', 'demo702', 61, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/demos/system/space.html', 1, 0);
INSERT INTO `rules` VALUES (63, '查看表', NULL, 'plugin\\admin\\app\\controller\\TableController@view', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (64, '查询表', NULL, 'plugin\\admin\\app\\controller\\TableController@show', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (65, '创建表', NULL, 'plugin\\admin\\app\\controller\\TableController@create', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (66, '修改表', NULL, 'plugin\\admin\\app\\controller\\TableController@modify', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (67, '一键菜单', NULL, 'plugin\\admin\\app\\controller\\TableController@crud', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (68, '查询记录', NULL, 'plugin\\admin\\app\\controller\\TableController@select', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (69, '插入记录', NULL, 'plugin\\admin\\app\\controller\\TableController@insert', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (70, '更新记录', NULL, 'plugin\\admin\\app\\controller\\TableController@update', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (71, '删除记录', NULL, 'plugin\\admin\\app\\controller\\TableController@delete', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (72, '删除表', NULL, 'plugin\\admin\\app\\controller\\TableController@drop', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (73, '表摘要', NULL, 'plugin\\admin\\app\\controller\\TableController@schema', 2, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (74, '插入', NULL, 'plugin\\admin\\app\\controller\\AdminController@insert', 4, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (75, '更新', NULL, 'plugin\\admin\\app\\controller\\AdminController@update', 4, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (76, '删除', NULL, 'plugin\\admin\\app\\controller\\AdminController@delete', 4, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (77, '插入', NULL, 'plugin\\admin\\app\\controller\\RoleController@insert', 5, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (78, '更新', NULL, 'plugin\\admin\\app\\controller\\RoleController@update', 5, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (79, '删除', NULL, 'plugin\\admin\\app\\controller\\RoleController@delete', 5, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (80, '获取角色权限', NULL, 'plugin\\admin\\app\\controller\\RoleController@rules', 5, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (81, '查询', NULL, 'plugin\\admin\\app\\controller\\RuleController@select', 6, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (82, '添加', NULL, 'plugin\\admin\\app\\controller\\RuleController@insert', 6, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (83, '更新', NULL, 'plugin\\admin\\app\\controller\\RuleController@update', 6, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (84, '删除', NULL, 'plugin\\admin\\app\\controller\\RuleController@delete', 6, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (85, '插入', NULL, 'plugin\\admin\\app\\controller\\UserController@insert', 8, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (86, '更新', NULL, 'plugin\\admin\\app\\controller\\UserController@update', 8, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (87, '查询', NULL, 'plugin\\admin\\app\\controller\\UserController@select', 8, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (88, '删除', NULL, 'plugin\\admin\\app\\controller\\UserController@delete', 8, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (89, '更新', NULL, 'plugin\\admin\\app\\controller\\AccountController@update', 10, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (90, '修改密码', NULL, 'plugin\\admin\\app\\controller\\AccountController@password', 10, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (91, '查询', NULL, 'plugin\\admin\\app\\controller\\AccountController@select', 10, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (92, '添加', NULL, 'plugin\\admin\\app\\controller\\AccountController@insert', 10, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (93, '删除', NULL, 'plugin\\admin\\app\\controller\\AccountController@delete', 10, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (94, '浏览附件', NULL, 'plugin\\admin\\app\\controller\\UploadController@attachment', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (95, '查询附件', NULL, 'plugin\\admin\\app\\controller\\UploadController@select', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (96, '更新附件', NULL, 'plugin\\admin\\app\\controller\\UploadController@update', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (97, '添加附件', NULL, 'plugin\\admin\\app\\controller\\UploadController@insert', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (98, '上传文件', NULL, 'plugin\\admin\\app\\controller\\UploadController@file', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (99, '上传图片', NULL, 'plugin\\admin\\app\\controller\\UploadController@image', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (100, '上传头像', NULL, 'plugin\\admin\\app\\controller\\UploadController@avatar', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (101, '删除附件', NULL, 'plugin\\admin\\app\\controller\\UploadController@delete', 11, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (102, '查询', NULL, 'plugin\\admin\\app\\controller\\DictController@select', 12, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (103, '插入', NULL, 'plugin\\admin\\app\\controller\\DictController@insert', 12, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (104, '更新', NULL, 'plugin\\admin\\app\\controller\\DictController@update', 12, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (105, '删除', NULL, 'plugin\\admin\\app\\controller\\DictController@delete', 12, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (106, '更改', NULL, 'plugin\\admin\\app\\controller\\ConfigController@update', 13, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (107, '列表', NULL, 'plugin\\admin\\app\\controller\\PluginController@list', 15, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (108, '安装', NULL, 'plugin\\admin\\app\\controller\\PluginController@install', 15, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (109, '卸载', NULL, 'plugin\\admin\\app\\controller\\PluginController@uninstall', 15, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (110, '支付', NULL, 'plugin\\admin\\app\\controller\\PluginController@pay', 15, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (111, '登录官网', NULL, 'plugin\\admin\\app\\controller\\PluginController@login', 15, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (112, '获取已安装的插件列表', NULL, 'plugin\\admin\\app\\controller\\PluginController@getInstalledPlugins', 15, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (113, '表单构建', NULL, 'plugin\\admin\\app\\controller\\DevController@formBuild', 17, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (114, '系统管理', 'layui-icon-release', 'systems', 0, '2025-03-07 14:39:52', '2025-03-07 14:46:59', '', 0, 0);
INSERT INTO `rules` VALUES (115, '轮播图', '', 'plugin\\admin\\app\\controller\\BannerController', 114, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/banner/index', 1, 0);
INSERT INTO `rules` VALUES (116, '插入', NULL, 'plugin\\admin\\app\\controller\\BannerController@insert', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (117, '更新', NULL, 'plugin\\admin\\app\\controller\\BannerController@update', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (118, '查询', NULL, 'plugin\\admin\\app\\controller\\BannerController@select', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (119, '删除', NULL, 'plugin\\admin\\app\\controller\\BannerController@delete', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (120, '系统设置', NULL, 'plugin\\admin\\app\\controller\\SystemConfigController', 114, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/systemConfig/index', 1, 0);
INSERT INTO `rules` VALUES (121, '公告', '', 'plugin\\admin\\app\\controller\\NoticeController', 114, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/notice/index', 1, 0);
INSERT INTO `rules` VALUES (122, '插入', NULL, 'plugin\\admin\\app\\controller\\NoticeController@insert', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (123, '更新', NULL, 'plugin\\admin\\app\\controller\\NoticeController@update', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (124, '查询', NULL, 'plugin\\admin\\app\\controller\\NoticeController@select', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (125, '删除', NULL, 'plugin\\admin\\app\\controller\\NoticeController@delete', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (126, '更改', NULL, 'plugin\\admin\\app\\controller\\SystemConfigController@update', 120, '2025-03-07 18:00:02', '2025-03-07 18:00:02', NULL, 2, 0);

-- ----------------------------
-- Table structure for transaction_logs
-- ----------------------------
DROP TABLE IF EXISTS `transaction_logs`;
CREATE TABLE `transaction_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `transaction_id` int(11) NULL DEFAULT NULL,
  `coin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bonus` decimal(10, 6) NULL DEFAULT 0.000000,
  `rate` decimal(10, 6) NULL DEFAULT NULL,
  `transaction_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '质押记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_logs
-- ----------------------------
INSERT INTO `transaction_logs` VALUES (1, 10, 1, 'ONE', 1.290000, 0.080000, 'CKB', 1741159440, '2025-03-05 15:24:00', '2025-03-05 15:24:00');

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `coin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(10, 6) NULL DEFAULT 0.000000,
  `bonus` decimal(10, 6) NULL DEFAULT 0.000000,
  `day` int(11) NULL DEFAULT 0 COMMENT '总天数',
  `run_day` int(11) NULL DEFAULT 0 COMMENT '当前执行天数',
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `runtime` int(11) NULL DEFAULT 0,
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '1正常 2完成',
  `transaction_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '质押记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES (1, 10, 'ONE', 500.000000, 1.290000, 15, 1, 1741155354, 1741104000, 1, 'CKB', '2025-03-05 14:15:54', '2025-03-05 14:15:54');

-- ----------------------------
-- Table structure for uploads
-- ----------------------------
DROP TABLE IF EXISTS `uploads`;
CREATE TABLE `uploads`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件',
  `admin_id` int(11) NULL DEFAULT NULL COMMENT '管理员',
  `file_size` int(11) NOT NULL COMMENT '文件大小',
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'mime类型',
  `image_width` int(11) NULL DEFAULT NULL COMMENT '图片宽度',
  `image_height` int(11) NULL DEFAULT NULL COMMENT '图片高度',
  `ext` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '扩展名',
  `storage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `created_at` date NULL DEFAULT NULL COMMENT '上传时间',
  `category` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '类别',
  `updated_at` date NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `category`(`category`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  INDEX `ext`(`ext`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of uploads
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `identity` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `is_real` tinyint(1) NULL DEFAULT 0 COMMENT '1真实用户',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '-1禁用,0正常,1异常',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '1',
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '最后登录IP',
  `last_login_at` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  UNIQUE INDEX `identity`(`identity`) USING BTREE,
  INDEX `inx_fields`(`id`, `pid`, `identity`, `status`, `level`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '1', 0, 1, 0, 1, '/app/admin/upload/avatar/202503/67ca972bafce.md.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-07 14:50:21');
INSERT INTO `users` VALUES (2, '2', 1, 1, 0, 0, '/images/avatars/avatar5.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (3, '3', 1, 1, 0, 0, '/images/avatars/avatar4.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (4, '4', 1, 1, 0, 0, '/images/avatars/avatar2.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (5, '5', 4, 1, 0, 6, '/images/avatars/avatar2.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (6, '6', 5, 1, 0, 5, '/images/avatars/avatar5.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (7, '7', 6, 1, 0, 4, '/images/avatars/avatar1.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (8, '8', 7, 1, 0, 3, '/images/avatars/avatar0.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (9, '9', 8, 1, 0, 2, '/images/avatars/avatar1.png', '', 'zh_CN', NULL, NULL, '2025-03-05 14:10:30', '2025-03-05 14:10:30');
INSERT INTO `users` VALUES (10, '10', 9, 1, 0, 1, '/images/avatars/avatar3.png', '', 'zh_CN', '127.0.0.1', '2025-03-07 14:16:09', '2025-03-05 14:10:30', '2025-03-07 14:16:09');

-- ----------------------------
-- Table structure for withdraws
-- ----------------------------
DROP TABLE IF EXISTS `withdraws`;
CREATE TABLE `withdraws`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(15, 6) UNSIGNED NULL DEFAULT NULL,
  `fee` decimal(10, 6) NULL DEFAULT 0.000000 COMMENT '手续费',
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家提现表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of withdraws
-- ----------------------------
INSERT INTO `withdraws` VALUES (1, 10, 'USDT', 1.000000, 0.000000, 1741329314, 0, '2025-03-07 14:35:14', '2025-03-07 14:35:14');

SET FOREIGN_KEY_CHECKS = 1;
