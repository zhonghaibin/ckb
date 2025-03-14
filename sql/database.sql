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

 Date: 14/03/2025 16:31:09
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
INSERT INTO `admins` VALUES (1, 'superadmin', '超级管理员', '$2y$10$TVMTYImaeleeoSEbEcDN/eHHgv4lMNCJdMD0uOF.EbVYUeA5b1A.y', '/app/admin/avatar.png', NULL, NULL, '2025-03-05 09:49:24', '2025-03-13 11:26:56', '2025-03-13 11:26:56', NULL);
INSERT INTO `admins` VALUES (2, 'admin', 'admin', '$2y$10$lyohfF1VwjhKMHXf0zzUPuNC1UUwaoN1UBN2GyNcWjG4jj7107rea', '/app/admin/avatar.png', '', '', '2025-03-06 16:24:29', '2025-03-11 15:23:26', '2025-03-11 15:23:26', NULL);

-- ----------------------------
-- Table structure for assets
-- ----------------------------
DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(20, 6) UNSIGNED NULL DEFAULT 0.000000 COMMENT '余额',
  `bonus` decimal(20, 6) UNSIGNED NULL DEFAULT 0.000000 COMMENT '收益金额',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `inx_Fields`(`id`, `user_id`, `coin`, `amount`, `bonus`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家钱包' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of assets
-- ----------------------------
INSERT INTO `assets` VALUES (1, 1, 'USDT', 10000.843186, 0.843186, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (2, 1, 'ONE', 10000.451613, 0.451613, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (3, 1, 'CKB', 10000.451613, 0.451613, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (4, 2, 'USDT', 10000.766533, 0.766533, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (5, 2, 'ONE', 10000.387097, 0.387097, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (6, 2, 'CKB', 10000.387097, 0.387097, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (7, 3, 'USDT', 10000.536573, 0.536573, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (8, 3, 'ONE', 10000.258065, 0.258065, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (9, 3, 'CKB', 10000.258065, 0.258065, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (10, 4, 'USDT', 10000.459920, 0.459920, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (11, 4, 'ONE', 10000.193548, 0.193548, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (12, 4, 'CKB', 10000.193548, 0.193548, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (13, 5, 'USDT', 10000.383266, 0.383266, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (14, 5, 'ONE', 10000.129032, 0.129032, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (15, 5, 'CKB', 10000.129032, 0.129032, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (16, 6, 'USDT', 10000.306613, 0.306613, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (17, 6, 'ONE', 10000.103226, 0.103226, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (18, 6, 'CKB', 10000.103226, 0.103226, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (19, 7, 'USDT', 10000.229960, 0.229960, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (20, 7, 'ONE', 10000.077419, 0.077419, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (21, 7, 'CKB', 10000.077419, 0.077419, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (22, 8, 'USDT', 10000.153307, 0.153307, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (23, 8, 'ONE', 10000.051613, 0.051613, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (24, 8, 'CKB', 10000.051613, 0.051613, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (25, 9, 'USDT', 10000.383266, 0.383266, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (26, 9, 'ONE', 10000.283871, 0.283871, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (27, 9, 'CKB', 10000.283871, 0.283871, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `assets` VALUES (28, 10, 'USDT', 9197.533065, 1.533065, '2025-03-13 18:12:21', '2025-03-14 10:31:03');
INSERT INTO `assets` VALUES (29, 10, 'ONE', 9501.290323, 1.290323, '2025-03-13 18:12:21', '2025-03-13 18:13:09');
INSERT INTO `assets` VALUES (30, 10, 'CKB', 9501.290323, 1.290323, '2025-03-13 18:12:21', '2025-03-13 18:13:15');
INSERT INTO `assets` VALUES (31, 11, 'USDT', 0.000000, 0.000000, '2025-03-14 13:32:37', '2025-03-14 16:24:47');
INSERT INTO `assets` VALUES (32, 11, 'ONE', 0.000000, 0.000000, '2025-03-14 13:32:37', '2025-03-14 13:32:37');
INSERT INTO `assets` VALUES (33, 11, 'CKB', 0.000000, 0.000000, '2025-03-14 13:32:37', '2025-03-14 13:32:37');

-- ----------------------------
-- Table structure for assets_logs
-- ----------------------------
DROP TABLE IF EXISTS `assets_logs`;
CREATE TABLE `assets_logs`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(20, 8) NULL DEFAULT 0.00000000,
  `balance` decimal(20, 8) NULL DEFAULT 0.00000000,
  `rate` decimal(15, 8) NULL DEFAULT 0.00000000,
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
) ENGINE = InnoDB AUTO_INCREMENT = 44 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '钱包变动记录表(账户发生变化时产生)' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of assets_logs
-- ----------------------------
INSERT INTO `assets_logs` VALUES (1, 10, 'ONE', -500.00000000, 9500.00000000, 0.00000000, 1, 0, 0, 0, 0, 4, '下单', 1741860789, '2025-03-13 18:13:09', '2025-03-13 18:13:09');
INSERT INTO `assets_logs` VALUES (2, 10, 'CKB', -500.00000000, 9500.00000000, 0.00000000, 2, 0, 0, 0, 0, 4, '下单', 1741860795, '2025-03-13 18:13:15', '2025-03-13 18:13:15');
INSERT INTO `assets_logs` VALUES (3, 10, 'USDT', -500.00000000, 9500.00000000, 0.00000000, 3, 0, 0, 0, 0, 4, '下单', 1741860801, '2025-03-13 18:13:21', '2025-03-13 18:13:21');
INSERT INTO `assets_logs` VALUES (4, 10, 'ONE', 1.29032300, 9501.29032300, 0.08000000, 1, 1, 0, 0, 0, 6, '每日收益', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (5, 9, 'ONE', 0.25806500, 10000.25806500, 0.20000000, 1, 1, 0, 0, 0, 7, '直推收益', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (6, 9, 'ONE', 0.02580600, 10000.28387100, 0.02000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (7, 8, 'ONE', 0.05161300, 10000.05161300, 0.04000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (8, 7, 'ONE', 0.07741900, 10000.07741900, 0.06000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (9, 6, 'ONE', 0.10322600, 10000.10322600, 0.08000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (10, 5, 'ONE', 0.12903200, 10000.12903200, 0.10000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (11, 4, 'ONE', 0.19354800, 10000.19354800, 0.15000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (12, 3, 'ONE', 0.25806500, 10000.25806500, 0.20000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (13, 2, 'ONE', 0.38709700, 10000.38709700, 0.30000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (14, 1, 'ONE', 0.38709700, 10000.38709700, 0.30000000, 1, 1, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (15, 1, 'ONE', 0.06451600, 10000.45161300, 0.05000000, 1, 1, 0, 0, 0, 9, '平级奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (16, 10, 'CKB', 1.29032300, 9501.29032300, 0.08000000, 2, 2, 0, 0, 0, 6, '每日收益', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (17, 9, 'CKB', 0.25806500, 10000.25806500, 0.20000000, 2, 2, 0, 0, 0, 7, '直推收益', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (18, 9, 'CKB', 0.02580600, 10000.28387100, 0.02000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (19, 8, 'CKB', 0.05161300, 10000.05161300, 0.04000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (20, 7, 'CKB', 0.07741900, 10000.07741900, 0.06000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (21, 6, 'CKB', 0.10322600, 10000.10322600, 0.08000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (22, 5, 'CKB', 0.12903200, 10000.12903200, 0.10000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (23, 4, 'CKB', 0.19354800, 10000.19354800, 0.15000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (24, 3, 'CKB', 0.25806500, 10000.25806500, 0.20000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (25, 2, 'CKB', 0.38709700, 10000.38709700, 0.30000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (26, 1, 'CKB', 0.38709700, 10000.38709700, 0.30000000, 2, 2, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (27, 1, 'CKB', 0.06451600, 10000.45161300, 0.05000000, 2, 2, 0, 0, 0, 9, '平级奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (28, 10, 'USDT', 1.53306500, 9501.53306500, 0.09510000, 3, 3, 0, 0, 0, 6, '每日收益', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (29, 9, 'USDT', 0.30661300, 10000.30661300, 0.20000000, 3, 3, 0, 0, 0, 7, '直推收益', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (30, 9, 'USDT', 0.07665300, 10000.38326600, 0.05000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (31, 8, 'USDT', 0.15330700, 10000.15330700, 0.10000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (32, 7, 'USDT', 0.22996000, 10000.22996000, 0.15000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (33, 6, 'USDT', 0.30661300, 10000.30661300, 0.20000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (34, 5, 'USDT', 0.38326600, 10000.38326600, 0.25000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (35, 4, 'USDT', 0.45992000, 10000.45992000, 0.30000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (36, 3, 'USDT', 0.53657300, 10000.53657300, 0.35000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (37, 2, 'USDT', 0.76653300, 10000.76653300, 0.50000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (38, 1, 'USDT', 0.76653300, 10000.76653300, 0.50000000, 3, 3, 0, 0, 0, 8, '极差奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (39, 1, 'USDT', 0.07665300, 10000.84318600, 0.05000000, 3, 3, 0, 0, 0, 9, '平级奖', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `assets_logs` VALUES (40, 10, 'USDT', -100.00000000, 9401.53306500, 0.00000000, 0, 0, 0, 0, 1, 2, '提现', 1741918894, '2025-03-14 10:21:34', '2025-03-14 10:21:34');
INSERT INTO `assets_logs` VALUES (41, 10, 'USDT', -102.00000000, 9301.53306500, 0.00000000, 0, 0, 0, 0, 2, 2, '提现', 1741919366, '2025-03-14 10:29:26', '2025-03-14 10:29:26');
INSERT INTO `assets_logs` VALUES (42, 10, 'USDT', -102.00000000, 9199.53306500, 0.00000000, 0, 0, 0, 0, 3, 2, '提现', 1741919463, '2025-03-14 10:31:03', '2025-03-14 10:31:03');
INSERT INTO `assets_logs` VALUES (43, 11, 'USDT', 0.00000000, 0.00000000, 0.00000000, 0, 0, 0, 8, 0, 1, '充值', 1741940687, '2025-03-14 16:24:47', '2025-03-14 16:24:47');

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '轮播图' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of banners
-- ----------------------------
INSERT INTO `banners` VALUES (1, '测试图片', '/app/admin/upload/img/20250312/67d16c720833.png', 1, '2025-03-12 14:13:54', '2025-03-12 19:13:55');
INSERT INTO `banners` VALUES (2, '1', '/app/admin/upload/img/20250312/67d16c7a5f9b.png', 1, '2025-03-12 19:14:04', '2025-03-12 19:14:04');

-- ----------------------------
-- Table structure for exchanges
-- ----------------------------
DROP TABLE IF EXISTS `exchanges`;
CREATE TABLE `exchanges`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `from_coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `to_coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `from_amount` decimal(20, 8) NULL DEFAULT 0.00000000,
  `to_amount` decimal(20, 8) NULL DEFAULT 0.00000000 COMMENT '兑换后的金额',
  `rate` decimal(15, 8) NULL DEFAULT 0.00000000 COMMENT '兑换业务',
  `fee` decimal(20, 8) NULL DEFAULT 0.00000000 COMMENT '手续费',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '1兑换成功',
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`id`, `user_id`, `from_coin`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '兑换' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of exchanges
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '登录日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of login_logs
-- ----------------------------
INSERT INTO `login_logs` VALUES (1, 10, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741860776, '2025-03-13 18:12:56', '2025-03-13 18:12:56');
INSERT INTO `login_logs` VALUES (2, 10, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741918888, '2025-03-14 10:21:28', '2025-03-14 10:21:28');
INSERT INTO `login_logs` VALUES (3, 1, 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', '192.168.3.35', 1741921814, '2025-03-14 11:10:14', '2025-03-14 11:10:14');
INSERT INTO `login_logs` VALUES (4, 10, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741924169, '2025-03-14 11:49:29', '2025-03-14 11:49:29');
INSERT INTO `login_logs` VALUES (5, 1, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741924541, '2025-03-14 11:55:41', '2025-03-14 11:55:41');
INSERT INTO `login_logs` VALUES (6, 10, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741924545, '2025-03-14 11:55:45', '2025-03-14 11:55:45');
INSERT INTO `login_logs` VALUES (7, 10, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741930092, '2025-03-14 13:28:12', '2025-03-14 13:28:12');
INSERT INTO `login_logs` VALUES (8, 11, 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', '192.168.3.35', 1741930357, '2025-03-14 13:32:37', '2025-03-14 13:32:37');
INSERT INTO `login_logs` VALUES (9, 11, 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', '192.168.3.35', 1741930871, '2025-03-14 13:41:11', '2025-03-14 13:41:11');
INSERT INTO `login_logs` VALUES (10, 10, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741930897, '2025-03-14 13:41:37', '2025-03-14 13:41:37');
INSERT INTO `login_logs` VALUES (11, 11, 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', '192.168.3.35', 1741932805, '2025-03-14 14:13:25', '2025-03-14 14:13:25');
INSERT INTO `login_logs` VALUES (12, 10, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.10', 1741935359, '2025-03-14 14:55:59', '2025-03-14 14:55:59');
INSERT INTO `login_logs` VALUES (13, 11, 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', '192.168.3.35', 1741936416, '2025-03-14 15:13:36', '2025-03-14 15:13:36');
INSERT INTO `login_logs` VALUES (14, 11, 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', '192.168.3.35', 1741936930, '2025-03-14 15:22:10', '2025-03-14 15:22:10');
INSERT INTO `login_logs` VALUES (15, 11, 'Apifox/1.0.0 (https://apifox.com)', '192.168.3.35', 1741937102, '2025-03-14 15:25:02', '2025-03-14 15:25:02');
INSERT INTO `login_logs` VALUES (16, 11, 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', '192.168.3.35', 1741937292, '2025-03-14 15:28:12', '2025-03-14 15:28:12');

-- ----------------------------
-- Table structure for notices
-- ----------------------------
DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '语言',
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '公告内容' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of notices
-- ----------------------------
INSERT INTO `notices` VALUES (1, 'zh_CN', '测试公告', '<p>测试内容啊</p>', 1, '2025-03-12 14:12:18', '2025-03-12 19:11:52');
INSERT INTO `notices` VALUES (2, 'en', '34', '<p>34234</p>', 1, '2025-03-13 13:55:47', '2025-03-13 13:57:31');

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
) ENGINE = InnoDB AUTO_INCREMENT = 134 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '选项表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `options` VALUES (1, 'system_config', '{\"logo\":{\"title\":\"\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"image\":\"\\/app\\/admin\\/admin\\/images\\/logo.png\",\"icp\":\"\",\"beian\":\"\",\"footer_txt\":\"\"},\"menu\":{\"data\":\"\\/app\\/admin\\/rule\\/get\",\"accordion\":true,\"collapse\":false,\"control\":false,\"controlWidth\":2000,\"select\":0,\"async\":true},\"tab\":{\"enable\":true,\"keepState\":true,\"preload\":false,\"session\":true,\"max\":\"30\",\"index\":{\"id\":\"0\",\"href\":\"\\/app\\/admin\\/index\\/dashboard\",\"title\":\"\\u4eea\\u8868\\u76d8\"}},\"theme\":{\"defaultColor\":\"2\",\"defaultMenu\":\"light-theme\",\"defaultHeader\":\"light-theme\",\"allowCustom\":true,\"banner\":false},\"colors\":[{\"id\":\"1\",\"color\":\"#36b368\",\"second\":\"#f0f9eb\"},{\"id\":\"2\",\"color\":\"#2d8cf0\",\"second\":\"#ecf5ff\"},{\"id\":\"3\",\"color\":\"#f6ad55\",\"second\":\"#fdf6ec\"},{\"id\":\"4\",\"color\":\"#f56c6c\",\"second\":\"#fef0f0\"},{\"id\":\"5\",\"color\":\"#3963bc\",\"second\":\"#ecf5ff\"}],\"other\":{\"keepLoad\":\"500\",\"autoHead\":false,\"footer\":false},\"header\":{\"message\":false}}', '2022-12-05 14:49:01', '2025-03-10 18:07:53');
INSERT INTO `options` VALUES (2, 'table_form_schema_wa_users', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false},\"username\":{\"field\":\"username\",\"_field_id\":\"1\",\"comment\":\"用户名\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"nickname\":{\"field\":\"nickname\",\"_field_id\":\"2\",\"comment\":\"昵称\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"password\":{\"field\":\"password\",\"_field_id\":\"3\",\"comment\":\"密码\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"sex\":{\"field\":\"sex\",\"_field_id\":\"4\",\"comment\":\"性别\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/dict\\/get\\/sex\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"avatar\":{\"field\":\"avatar\",\"_field_id\":\"5\",\"comment\":\"头像\",\"control\":\"uploadImage\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/avatar\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"email\":{\"field\":\"email\",\"_field_id\":\"6\",\"comment\":\"邮箱\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"mobile\":{\"field\":\"mobile\",\"_field_id\":\"7\",\"comment\":\"手机\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"level\":{\"field\":\"level\",\"_field_id\":\"8\",\"comment\":\"等级\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"birthday\":{\"field\":\"birthday\",\"_field_id\":\"9\",\"comment\":\"生日\",\"control\":\"datePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"money\":{\"field\":\"money\",\"_field_id\":\"10\",\"comment\":\"余额(元)\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"score\":{\"field\":\"score\",\"_field_id\":\"11\",\"comment\":\"积分\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"last_time\":{\"field\":\"last_time\",\"_field_id\":\"12\",\"comment\":\"登录时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"last_ip\":{\"field\":\"last_ip\",\"_field_id\":\"13\",\"comment\":\"登录ip\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"join_time\":{\"field\":\"join_time\",\"_field_id\":\"14\",\"comment\":\"注册时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"join_ip\":{\"field\":\"join_ip\",\"_field_id\":\"15\",\"comment\":\"注册ip\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false},\"token\":{\"field\":\"token\",\"_field_id\":\"16\",\"comment\":\"token\",\"control\":\"input\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"17\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"18\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"between\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"role\":{\"field\":\"role\",\"_field_id\":\"19\",\"comment\":\"角色\",\"control\":\"inputNumber\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"status\":{\"field\":\"status\",\"_field_id\":\"20\",\"comment\":\"禁用\",\"control\":\"switch\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-23 15:28:13');
INSERT INTO `options` VALUES (3, 'table_form_schema_wa_roles', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"角色组\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"rules\":{\"field\":\"rules\",\"_field_id\":\"2\",\"comment\":\"权限\",\"control\":\"treeSelectMulti\",\"control_args\":\"url:\\/app\\/admin\\/rule\\/get?type=0,1,2\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"3\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"4\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"pid\":{\"field\":\"pid\",\"_field_id\":\"5\",\"comment\":\"父级\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/role\\/select?format=tree\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-19 14:24:25');
INSERT INTO `options` VALUES (4, 'table_form_schema_wa_rules', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"title\":{\"field\":\"title\",\"_field_id\":\"1\",\"comment\":\"标题\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"icon\":{\"field\":\"icon\",\"_field_id\":\"2\",\"comment\":\"图标\",\"control\":\"iconPicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"key\":{\"field\":\"key\",\"_field_id\":\"3\",\"comment\":\"标识\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"pid\":{\"field\":\"pid\",\"_field_id\":\"4\",\"comment\":\"上级菜单\",\"control\":\"treeSelect\",\"control_args\":\"\\/app\\/admin\\/rule\\/select?format=tree&type=0,1\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"5\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"6\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"href\":{\"field\":\"href\",\"_field_id\":\"7\",\"comment\":\"url\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"type\":{\"field\":\"type\",\"_field_id\":\"8\",\"comment\":\"类型\",\"control\":\"select\",\"control_args\":\"data:0:目录,1:菜单,2:权限\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"weight\":{\"field\":\"weight\",\"_field_id\":\"9\",\"comment\":\"排序\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:44:45');
INSERT INTO `options` VALUES (5, 'table_form_schema_wa_admins', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"ID\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"search_type\":\"between\",\"form_show\":false,\"searchable\":false},\"username\":{\"field\":\"username\",\"_field_id\":\"1\",\"comment\":\"用户名\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"nickname\":{\"field\":\"nickname\",\"_field_id\":\"2\",\"comment\":\"昵称\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"password\":{\"field\":\"password\",\"_field_id\":\"3\",\"comment\":\"密码\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"avatar\":{\"field\":\"avatar\",\"_field_id\":\"4\",\"comment\":\"头像\",\"control\":\"uploadImage\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/avatar\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"email\":{\"field\":\"email\",\"_field_id\":\"5\",\"comment\":\"邮箱\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"mobile\":{\"field\":\"mobile\",\"_field_id\":\"6\",\"comment\":\"手机\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"7\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"searchable\":true,\"search_type\":\"between\",\"list_show\":false,\"enable_sort\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"8\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"search_type\":\"normal\",\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"login_at\":{\"field\":\"login_at\",\"_field_id\":\"9\",\"comment\":\"登录时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"between\",\"enable_sort\":false,\"searchable\":false},\"status\":{\"field\":\"status\",\"_field_id\":\"10\",\"comment\":\"禁用\",\"control\":\"switch\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-23 15:36:48');
INSERT INTO `options` VALUES (6, 'table_form_schema_wa_options', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"键\",\"control\":\"input\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"value\":{\"field\":\"value\",\"_field_id\":\"2\",\"comment\":\"值\",\"control\":\"textArea\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"3\",\"comment\":\"创建时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"4\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:36:57');
INSERT INTO `options` VALUES (7, 'table_form_schema_wa_uploads', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"search_type\":\"normal\",\"form_show\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"名称\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"url\":{\"field\":\"url\",\"_field_id\":\"2\",\"comment\":\"文件\",\"control\":\"upload\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/file\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"admin_id\":{\"field\":\"admin_id\",\"_field_id\":\"3\",\"comment\":\"管理员\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/admin\\/select?format=select\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"file_size\":{\"field\":\"file_size\",\"_field_id\":\"4\",\"comment\":\"文件大小\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"between\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"mime_type\":{\"field\":\"mime_type\",\"_field_id\":\"5\",\"comment\":\"mime类型\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_width\":{\"field\":\"image_width\",\"_field_id\":\"6\",\"comment\":\"图片宽度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_height\":{\"field\":\"image_height\",\"_field_id\":\"7\",\"comment\":\"图片高度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"ext\":{\"field\":\"ext\",\"_field_id\":\"8\",\"comment\":\"扩展名\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"storage\":{\"field\":\"storage\",\"_field_id\":\"9\",\"comment\":\"存储位置\",\"control\":\"input\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"10\",\"comment\":\"上传时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"searchable\":true,\"search_type\":\"between\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false},\"category\":{\"field\":\"category\",\"_field_id\":\"11\",\"comment\":\"类别\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/dict\\/get\\/upload\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"12\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:47:45');
INSERT INTO `options` VALUES (8, 'table_form_schema_wa_uploads', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"search_type\":\"normal\",\"form_show\":false,\"searchable\":false},\"name\":{\"field\":\"name\",\"_field_id\":\"1\",\"comment\":\"名称\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"url\":{\"field\":\"url\",\"_field_id\":\"2\",\"comment\":\"文件\",\"control\":\"upload\",\"control_args\":\"url:\\/app\\/admin\\/upload\\/file\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"admin_id\":{\"field\":\"admin_id\",\"_field_id\":\"3\",\"comment\":\"管理员\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/admin\\/select?format=select\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"file_size\":{\"field\":\"file_size\",\"_field_id\":\"4\",\"comment\":\"文件大小\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"between\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"mime_type\":{\"field\":\"mime_type\",\"_field_id\":\"5\",\"comment\":\"mime类型\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_width\":{\"field\":\"image_width\",\"_field_id\":\"6\",\"comment\":\"图片宽度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"image_height\":{\"field\":\"image_height\",\"_field_id\":\"7\",\"comment\":\"图片高度\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false,\"searchable\":false},\"ext\":{\"field\":\"ext\",\"_field_id\":\"8\",\"comment\":\"扩展名\",\"control\":\"input\",\"control_args\":\"\",\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false,\"enable_sort\":false},\"storage\":{\"field\":\"storage\",\"_field_id\":\"9\",\"comment\":\"存储位置\",\"control\":\"input\",\"control_args\":\"\",\"search_type\":\"normal\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false,\"searchable\":false},\"created_at\":{\"field\":\"created_at\",\"_field_id\":\"10\",\"comment\":\"上传时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"searchable\":true,\"search_type\":\"between\",\"form_show\":false,\"list_show\":false,\"enable_sort\":false},\"category\":{\"field\":\"category\",\"_field_id\":\"11\",\"comment\":\"类别\",\"control\":\"select\",\"control_args\":\"url:\\/app\\/admin\\/dict\\/get\\/upload\",\"form_show\":true,\"list_show\":true,\"searchable\":true,\"search_type\":\"normal\",\"enable_sort\":false},\"updated_at\":{\"field\":\"updated_at\",\"_field_id\":\"12\",\"comment\":\"更新时间\",\"control\":\"dateTimePicker\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-08 11:47:45');
INSERT INTO `options` VALUES (9, 'dict_upload', '[{\"value\":\"1\",\"name\":\"图片\"}]', '2022-12-04 16:24:13', '2025-03-11 14:06:31');
INSERT INTO `options` VALUES (10, 'dict_sex', '[{\"value\":\"0\",\"name\":\"女\"},{\"value\":\"1\",\"name\":\"男\"}]', '2022-12-04 15:04:40', '2022-12-04 15:04:40');
INSERT INTO `options` VALUES (11, 'dict_status', '[{\"value\":\"0\",\"name\":\"正常\"},{\"value\":\"1\",\"name\":\"禁用\"}]', '2022-12-04 15:05:09', '2022-12-04 15:05:09');
INSERT INTO `options` VALUES (17, 'table_form_schema_wa_admin_roles', '{\"id\":{\"field\":\"id\",\"_field_id\":\"0\",\"comment\":\"主键\",\"control\":\"inputNumber\",\"control_args\":\"\",\"list_show\":true,\"enable_sort\":true,\"searchable\":true,\"search_type\":\"normal\",\"form_show\":false},\"role_id\":{\"field\":\"role_id\",\"_field_id\":\"1\",\"comment\":\"角色id\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false},\"admin_id\":{\"field\":\"admin_id\",\"_field_id\":\"2\",\"comment\":\"管理员id\",\"control\":\"inputNumber\",\"control_args\":\"\",\"form_show\":true,\"list_show\":true,\"search_type\":\"normal\",\"enable_sort\":false,\"searchable\":false}}', '2022-08-15 00:00:00', '2022-12-20 19:42:51');
INSERT INTO `options` VALUES (18, 'dict_dict_name', '[{\"value\":\"dict_name\",\"name\":\"字典名称\"},{\"value\":\"status\",\"name\":\"启禁用状态\"},{\"value\":\"sex\",\"name\":\"性别\"},{\"value\":\"upload\",\"name\":\"附件分类\"}]', '2022-08-15 00:00:00', '2022-12-20 19:42:51');
INSERT INTO `options` VALUES (20, 'dict_transaction_status', '[{\"value\":\"1\",\"name\":\"进行中\"},{\"value\":\"2\",\"name\":\"已完成\"}]', '2022-12-04 15:04:40', '2022-12-04 15:04:40');
INSERT INTO `options` VALUES (21, 'dict_exchange_status', '[{\"value\":\"0\",\"name\":\"进行中\"},{\"value\":\"1\",\"name\":\"已完成\"}]', '2022-12-04 15:04:40', '2022-12-04 15:04:40');
INSERT INTO `options` VALUES (22, 'dict_recharge_status', '[{\"value\":\"0\",\"name\":\"进行中\"},{\"value\":\"1\",\"name\":\"已完成\"},{\"value\":\"2\",\"name\":\"失败\"}]', '2022-12-04 15:04:40', '2022-12-04 15:04:40');
INSERT INTO `options` VALUES (114, 'dict_withdraw_status', '[{\"value\":\"0\",\"name\":\"待审核\"},{\"value\":\"1\",\"name\":\"已完成\"},{\"value\":\"2\",\"name\":\"失败\"}]', '2025-03-11 14:58:48', '2025-03-11 14:59:06');
INSERT INTO `options` VALUES (115, 'dict_user_level', '[{\"value\":\"0\",\"name\":\"Vip0\"},{\"value\":\"1\",\"name\":\"Vip1\"},{\"value\":\"2\",\"name\":\"Vip2\"},{\"value\":\"3\",\"name\":\"Vip3\"},{\"value\":\"4\",\"name\":\"Vip4\"},{\"value\":\"5\",\"name\":\"Vip5\"},{\"value\":\"6\",\"name\":\"Vip6\"},{\"value\":\"7\",\"name\":\"Vip7\"},{\"value\":\"8\",\"name\":\"Vip8\"},{\"value\":\"9\",\"name\":\"Vip9\"}]', '2025-03-11 15:06:39', '2025-03-11 15:06:39');
INSERT INTO `options` VALUES (131, 'config', '{\"base_info\":{\"maintenance_mode\":false,\"maintenance_message\":\"\\u7cfb\\u7edf\\u6b63\\u5728\\u7ef4\\u62a4\\uff0c\\u8bf7\\u7a0d\\u540e\\u518d\\u8bd5\\u3002\",\"web_url\":\"http:\\/\\/www.baidu.com\",\"share_url\":\"http:\\/\\/test.dev\\/code=\",\"wallet_address\":\"3e71CqSwTdfXxxh5HnYjqju31a2WRQZXt68TkkUTLHpG\",\"pledge_min_number\":\"500\",\"mev_min_number\":\"500\",\"exchange_min_number\":\"1\",\"withdraw_min_number\":\"100\",\"withdraw_fee_rate\":\"2\"},\"pledge\":{\"ckb\":{\"staticRate\":[{\"day\":\"15\",\"rate\":\"8\"},{\"day\":\"30\",\"rate\":\"12\"},{\"day\":\"60\",\"rate\":\"15\"}],\"directRate\":\"20\",\"levelDiffRate\":[{\"level\":\"Vip0\",\"rate\":\"0\"},{\"level\":\"Vip1\",\"rate\":\"2\"},{\"level\":\"Vip2\",\"rate\":\"4\"},{\"level\":\"Vip3\",\"rate\":\"6\"},{\"level\":\"Vip4\",\"rate\":\"8\"},{\"level\":\"Vip5\",\"rate\":\"10\"},{\"level\":\"Vip6\",\"rate\":\"15\"},{\"level\":\"Vip7\",\"rate\":\"20\"},{\"level\":\"Vip8\",\"rate\":\"25\"},{\"level\":\"Vip9\",\"rate\":\"30\"}],\"sameLevelRate\":\"5\"},\"one\":{\"staticRate\":[{\"day\":\"15\",\"rate\":\"8\"},{\"day\":\"30\",\"rate\":\"12\"},{\"day\":\"60\",\"rate\":\"15\"}],\"directRate\":\"20\",\"levelDiffRate\":[{\"level\":\"Vip0\",\"rate\":\"0\"},{\"level\":\"Vip1\",\"rate\":\"2\"},{\"level\":\"Vip2\",\"rate\":\"4\"},{\"level\":\"Vip3\",\"rate\":\"6\"},{\"level\":\"Vip4\",\"rate\":\"8\"},{\"level\":\"Vip5\",\"rate\":\"10\"},{\"level\":\"Vip6\",\"rate\":\"15\"},{\"level\":\"Vip7\",\"rate\":\"20\"},{\"level\":\"Vip8\",\"rate\":\"25\"},{\"level\":\"Vip9\",\"rate\":\"30\"}],\"sameLevelRate\":\"5\"}},\"mev\":{\"usdt\":{\"staticRate\":[{\"day\":\"1\",\"rate\":{\"min\":\"6\",\"max\":\"8\"}},{\"day\":\"15\",\"rate\":{\"min\":\"8\",\"max\":\"10\"}},{\"day\":\"30\",\"rate\":{\"min\":\"10\",\"max\":\"13\"}}],\"directRate\":\"20\",\"levelDiffRate\":[{\"level\":\"Vip0\",\"rate\":\"0\"},{\"level\":\"Vip1\",\"rate\":\"5\"},{\"level\":\"Vip2\",\"rate\":\"10\"},{\"level\":\"Vip3\",\"rate\":\"15\"},{\"level\":\"Vip4\",\"rate\":\"20\"},{\"level\":\"Vip5\",\"rate\":\"25\"},{\"level\":\"Vip6\",\"rate\":\"30\"},{\"level\":\"Vip7\",\"rate\":\"35\"},{\"level\":\"Vip8\",\"rate\":\"40\"},{\"level\":\"Vip9\",\"rate\":\"50\"}],\"sameLevelRate\":\"5\"}}}', '2025-03-12 17:39:25', '2025-03-14 15:57:18');
INSERT INTO `options` VALUES (132, 'dict_coin', '[{\"value\":\"USDT\",\"name\":\"USDT\"},{\"value\":\"ONE\",\"name\":\"ONE\"},{\"value\":\"CBK\",\"name\":\"CBK\"}]', '2025-03-13 11:36:56', '2025-03-13 11:36:56');
INSERT INTO `options` VALUES (133, 'dict_lang', '[{\"value\":\"en\",\"name\":\"英文\"},{\"value\":\"zh_CN\",\"name\":\"中文\"}]', '2025-03-13 13:46:46', '2025-03-13 13:46:46');

-- ----------------------------
-- Table structure for recharges
-- ----------------------------
DROP TABLE IF EXISTS `recharges`;
CREATE TABLE `recharges`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(20, 6) NULL DEFAULT NULL,
  `remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '交易签名',
  `user_wallet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '付款账户',
  `datetime` int(11) NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家充值记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of recharges
-- ----------------------------
INSERT INTO `recharges` VALUES (1, 10, 'USDT', 0.001000, '', 0, '4Xt9m7gLNMHAc4GzEgRq9URa6hW2NxhWW8f5f6SCearHow86yuRkhCGbPkRFP1jNXtiwA6B8tnbADVpJEdj87mtf', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741936573, '2025-03-14 15:16:13', '2025-03-14 15:16:13');
INSERT INTO `recharges` VALUES (2, 11, 'USDT', 0.001000, '', 0, '4Xt9m7gLNMHAc4GzEgRq9URa6hW2NxhWW8f5f6SCearHow86yuRkhCGbPkRFP1jNXtiwA6B8tnbADVpJEdj87mtf', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741937132, '2025-03-14 15:25:32', '2025-03-14 15:25:32');
INSERT INTO `recharges` VALUES (3, 11, 'USDT', 0.001000, '', 0, '4Xt9m7gLNMHAc4GzEgRq9URa6hW2NxhWW8f5f6SCearHow86yuRkhCGbPkRFP1jNXtiwA6B8tnbADVpJEdj87mtf', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741937190, '2025-03-14 15:26:30', '2025-03-14 15:26:30');
INSERT INTO `recharges` VALUES (4, 11, 'USDT', 1.000000, '', 0, '3Qs8Ud1Ju5H9rdYPotGyBYSXWedHw2yG8SgkFLVwiVRtvekbo9Vhjh2EuSRozN9M1trVuAowjkAanMghsLkNyrVa', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741937217, '2025-03-14 15:26:57', '2025-03-14 15:26:57');
INSERT INTO `recharges` VALUES (5, 10, 'USDT', 0.001000, '', 0, '4Xt9m7gLNMHAc4GzEgRq9URa6hW2NxhWW8f5f6SCearHow86yuRkhCGbPkRFP1jNXtiwA6B8tnbADVpJEdj87mtf', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741937368, '2025-03-14 15:29:28', '2025-03-14 15:29:28');
INSERT INTO `recharges` VALUES (6, 10, 'USDT', 0.001000, '', 0, '4Xt9m7gLNMHAc4GzEgRq9URa6hW2NxhWW8f5f6SCearHow86yuRkhCGbPkRFP1jNXtiwA6B8tnbADVpJEdj87mtf', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741937443, '2025-03-14 15:30:43', '2025-03-14 15:30:43');
INSERT INTO `recharges` VALUES (7, 11, 'USDT', 1.000000, '', 0, '3Qs8Ud1Ju5H9rdYPotGyBYSXWedHw2yG8SgkFLVwiVRtvekbo9Vhjh2EuSRozN9M1trVuAowjkAanMghsLkNyrVa', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741937627, '2025-03-14 15:33:47', '2025-03-14 15:33:47');
INSERT INTO `recharges` VALUES (8, 11, 'USDT', 1.000000, '', 0, '3ErvhTz5ADnf2869DfXejmDTEug1qz6eE3DVSiG3WPthDf97vfYCe7KE8gNx3WK3JdRg3Q17LChHDAJFUmN2PAjU', 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 1741939077, '2025-03-14 15:57:57', '2025-03-14 15:57:57');

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
INSERT INTO `roles` VALUES (2, '管理员', '85,86,87,88,175,176,177,178,89,90,91,92,93,94,95,96,97,98,99,100,101,116,117,118,119,122,123,124,125,126,121,129,132,133,146,147,151,152,153,154,155,131,137,138,148,149,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174', '2025-03-06 16:23:57', '2025-03-11 18:01:39', 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 179 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限规则' ROW_FORMAT = DYNAMIC;

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
INSERT INTO `rules` VALUES (87, '查询', NULL, 'plugin\\admin\\app\\controller\\UserController@select', 8, '2025-03-05 09:51:00', '2025-03-11 18:02:50', NULL, 2, 0);
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
INSERT INTO `rules` VALUES (115, '首页动图', '', 'plugin\\admin\\app\\controller\\BannerController', 114, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/banner/index', 1, 0);
INSERT INTO `rules` VALUES (116, '插入', NULL, 'plugin\\admin\\app\\controller\\BannerController@insert', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (117, '更新', NULL, 'plugin\\admin\\app\\controller\\BannerController@update', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (118, '查询', NULL, 'plugin\\admin\\app\\controller\\BannerController@select', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (119, '删除', NULL, 'plugin\\admin\\app\\controller\\BannerController@delete', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (120, '参数配置', NULL, 'plugin\\admin\\app\\controller\\SystemConfigController', 114, '2025-03-05 09:49:00', '2025-03-05 09:49:00', '/app/admin/systemConfig/index', 1, 0);
INSERT INTO `rules` VALUES (121, '公告管理', '', 'plugin\\admin\\app\\controller\\NoticeController', 114, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/notice/index', 1, 0);
INSERT INTO `rules` VALUES (122, '插入', NULL, 'plugin\\admin\\app\\controller\\NoticeController@insert', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (123, '更新', NULL, 'plugin\\admin\\app\\controller\\NoticeController@update', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (124, '查询', NULL, 'plugin\\admin\\app\\controller\\NoticeController@select', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (125, '删除', NULL, 'plugin\\admin\\app\\controller\\NoticeController@delete', 115, '2025-03-05 09:51:00', '2025-03-05 09:51:00', NULL, 2, 0);
INSERT INTO `rules` VALUES (126, '更改', NULL, 'plugin\\admin\\app\\controller\\SystemConfigController@update', 120, '2025-03-07 18:00:02', '2025-03-07 18:00:02', NULL, 2, 0);
INSERT INTO `rules` VALUES (127, '财务管理', 'layui-icon-rmb', 'finance', NULL, '2025-03-07 14:39:52', '2025-03-10 20:11:17', '', 0, 0);
INSERT INTO `rules` VALUES (128, '质押记录', '', 'plugin\\admin\\app\\controller\\PledgeController', 127, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/pledge/index', 1, 0);
INSERT INTO `rules` VALUES (129, '查询', NULL, 'plugin\\admin\\app\\controller\\PledgeController@select', 128, '2025-03-05 09:51:00', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (130, '套利记录', '', 'plugin\\admin\\app\\controller\\MevController', 127, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/mev/index', 1, 0);
INSERT INTO `rules` VALUES (131, '查询', NULL, 'plugin\\admin\\app\\controller\\MevController@select', 130, '2025-03-05 09:51:00', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (132, '浏览静态收益', NULL, 'plugin\\admin\\app\\controller\\PledgeController@staticIncome', 128, '2025-03-05 09:51:00', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (133, '查询静态收益', NULL, 'plugin\\admin\\app\\controller\\PledgeController@staticIncomes', 128, '2025-03-10 14:51:13', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (137, '浏览静态收益', NULL, 'plugin\\admin\\app\\controller\\MevController@staticIncome', 130, '2025-03-10 14:51:13', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (138, '查询静态收益', NULL, 'plugin\\admin\\app\\controller\\MevController@staticIncomes', 130, '2025-03-10 14:51:13', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (142, '兑换记录', '', 'plugin\\admin\\app\\controller\\ExchangeController', 127, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/exchange/index', 1, 0);
INSERT INTO `rules` VALUES (143, '充值记录', '', 'plugin\\admin\\app\\controller\\RechargeController', 127, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/recharge/index', 1, 0);
INSERT INTO `rules` VALUES (144, '提现记录', '', 'plugin\\admin\\app\\controller\\WithdrawController', 127, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/withdraw/index', 1, 0);
INSERT INTO `rules` VALUES (145, '资金明细', '', 'plugin\\admin\\app\\controller\\AssetsLogController', 127, '2025-03-07 14:42:32', '2025-03-07 15:07:35', '/app/admin/assetsLog/index', 1, 0);
INSERT INTO `rules` VALUES (146, '浏览动态收益', NULL, 'plugin\\admin\\app\\controller\\PledgeController@dynamicIncome', 128, '2025-03-10 14:51:13', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (147, '查询动态收益', NULL, 'plugin\\admin\\app\\controller\\PledgeController@dynamicIncomes', 128, '2025-03-10 14:51:13', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (148, '浏览动态收益', NULL, 'plugin\\admin\\app\\controller\\MevController@dynamicIncome', 130, '2025-03-10 14:51:13', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (149, '查询动态收益', NULL, 'plugin\\admin\\app\\controller\\MevController@dynamicIncomes', 130, '2025-03-10 14:51:13', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (151, '浏览收益', NULL, 'plugin\\admin\\app\\controller\\PledgeController@show', 128, '2025-03-10 20:10:26', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (152, '查询收益', NULL, 'plugin\\admin\\app\\controller\\PledgeController@ckbLogs', 128, '2025-03-10 20:10:26', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (153, '添加', NULL, 'plugin\\admin\\app\\controller\\PledgeController@insert', 128, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (154, '更新', NULL, 'plugin\\admin\\app\\controller\\PledgeController@update', 128, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (155, '删除', NULL, 'plugin\\admin\\app\\controller\\PledgeController@delete', 128, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (156, '添加', NULL, 'plugin\\admin\\app\\controller\\MevController@insert', 130, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (157, '更新', NULL, 'plugin\\admin\\app\\controller\\MevController@update', 130, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (158, '删除', NULL, 'plugin\\admin\\app\\controller\\MevController@delete', 130, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (159, '查询', NULL, 'plugin\\admin\\app\\controller\\ExchangeController@select', 142, '2025-03-10 20:10:27', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (160, '添加', NULL, 'plugin\\admin\\app\\controller\\ExchangeController@insert', 142, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (161, '更新', NULL, 'plugin\\admin\\app\\controller\\ExchangeController@update', 142, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (162, '删除', NULL, 'plugin\\admin\\app\\controller\\ExchangeController@delete', 142, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (163, '查询', NULL, 'plugin\\admin\\app\\controller\\RechargeController@select', 143, '2025-03-10 20:10:27', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (164, '添加', NULL, 'plugin\\admin\\app\\controller\\RechargeController@insert', 143, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (165, '更新', NULL, 'plugin\\admin\\app\\controller\\RechargeController@update', 143, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (166, '删除', NULL, 'plugin\\admin\\app\\controller\\RechargeController@delete', 143, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (167, '查询', NULL, 'plugin\\admin\\app\\controller\\WithdrawController@select', 144, '2025-03-10 20:10:27', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (168, '添加', NULL, 'plugin\\admin\\app\\controller\\WithdrawController@insert', 144, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (169, '更新', NULL, 'plugin\\admin\\app\\controller\\WithdrawController@update', 144, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (170, '删除', NULL, 'plugin\\admin\\app\\controller\\WithdrawController@delete', 144, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (171, '查询', NULL, 'plugin\\admin\\app\\controller\\AssetsLogController@select', 145, '2025-03-10 20:10:27', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (172, '添加', NULL, 'plugin\\admin\\app\\controller\\AssetsLogController@insert', 145, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (173, '更新', NULL, 'plugin\\admin\\app\\controller\\AssetsLogController@update', 145, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (174, '删除', NULL, 'plugin\\admin\\app\\controller\\AssetsLogController@delete', 145, '2025-03-10 20:10:27', '2025-03-10 20:10:27', NULL, 2, 0);
INSERT INTO `rules` VALUES (175, '浏览直推', NULL, 'plugin\\admin\\app\\controller\\UserController@direct', 8, '2025-03-10 20:10:26', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (176, '查询直推', NULL, 'plugin\\admin\\app\\controller\\UserController@directs', 8, '2025-03-10 20:10:26', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (177, '浏览团队', NULL, 'plugin\\admin\\app\\controller\\UserController@team', 8, '2025-03-10 20:10:26', '2025-03-11 17:25:57', NULL, 2, 0);
INSERT INTO `rules` VALUES (178, '查询团队', NULL, 'plugin\\admin\\app\\controller\\UserController@teams', 8, '2025-03-10 20:10:26', '2025-03-11 17:25:57', NULL, 2, 0);

-- ----------------------------
-- Table structure for transaction_logs
-- ----------------------------
DROP TABLE IF EXISTS `transaction_logs`;
CREATE TABLE `transaction_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `transaction_id` int(11) NULL DEFAULT NULL,
  `coin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bonus` decimal(20, 8) NULL DEFAULT 0.00000000,
  `rate` decimal(15, 8) NULL DEFAULT NULL,
  `transaction_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '交易收益' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_logs
-- ----------------------------
INSERT INTO `transaction_logs` VALUES (1, 10, 1, 'ONE', 1.29032300, 0.08000000, 'PLEDGE', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `transaction_logs` VALUES (2, 10, 2, 'CKB', 1.29032300, 0.08000000, 'PLEDGE', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');
INSERT INTO `transaction_logs` VALUES (3, 10, 3, 'USDT', 1.53306500, 0.09510000, 'PLEDGE', 1741917702, '2025-03-14 10:01:42', '2025-03-14 10:01:42');

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `coin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(20, 8) NULL DEFAULT 0.00000000,
  `bonus` decimal(20, 8) NULL DEFAULT 0.00000000,
  `day` int(11) NULL DEFAULT 0 COMMENT '总天数',
  `run_day` int(11) NULL DEFAULT 0 COMMENT '当前执行天数',
  `rates` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '执行参数',
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `runtime` int(11) NULL DEFAULT 0,
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '1正常 2完成',
  `transaction_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '交易记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES (1, 10, 'ONE', 500.00000000, 1.29032300, 15, 1, '{\"staticRate\":[{\"day\":\"15\",\"rate\":\"8\"},{\"day\":\"30\",\"rate\":\"12\"},{\"day\":\"60\",\"rate\":\"15\"}],\"directRate\":\"20\",\"levelDiffRate\":[{\"level\":\"Vip0\",\"rate\":\"0\"},{\"level\":\"Vip1\",\"rate\":\"2\"},{\"level\":\"Vip2\",\"rate\":\"4\"},{\"level\":\"Vip3\",\"rate\":\"6\"},{\"level\":\"Vip4\",\"rate\":\"8\"},{\"level\":\"Vip5\",\"rate\":\"10\"},{\"level\":\"Vip6\",\"rate\":\"15\"},{\"level\":\"Vip7\",\"rate\":\"20\"},{\"level\":\"Vip8\",\"rate\":\"25\"},{\"level\":\"Vip9\",\"rate\":\"30\"}],\"sameLevelRate\":\"5\"}', 1741860789, 1741881600, 1, 'PLEDGE', '2025-03-13 18:13:09', '2025-03-13 18:13:09');
INSERT INTO `transactions` VALUES (2, 10, 'CKB', 500.00000000, 1.29032300, 15, 1, '{\"staticRate\":[{\"day\":\"15\",\"rate\":\"8\"},{\"day\":\"30\",\"rate\":\"12\"},{\"day\":\"60\",\"rate\":\"15\"}],\"directRate\":\"20\",\"levelDiffRate\":[{\"level\":\"Vip0\",\"rate\":\"0\"},{\"level\":\"Vip1\",\"rate\":\"2\"},{\"level\":\"Vip2\",\"rate\":\"4\"},{\"level\":\"Vip3\",\"rate\":\"6\"},{\"level\":\"Vip4\",\"rate\":\"8\"},{\"level\":\"Vip5\",\"rate\":\"10\"},{\"level\":\"Vip6\",\"rate\":\"15\"},{\"level\":\"Vip7\",\"rate\":\"20\"},{\"level\":\"Vip8\",\"rate\":\"25\"},{\"level\":\"Vip9\",\"rate\":\"30\"}],\"sameLevelRate\":\"5\"}', 1741860795, 1741881600, 1, 'PLEDGE', '2025-03-13 18:13:15', '2025-03-13 18:13:15');
INSERT INTO `transactions` VALUES (3, 10, 'USDT', 500.00000000, 1.53306500, 15, 1, '{\"staticRate\":[{\"day\":\"1\",\"rate\":{\"min\":\"6\",\"max\":\"8\"}},{\"day\":\"15\",\"rate\":{\"min\":\"8\",\"max\":\"10\"}},{\"day\":\"30\",\"rate\":{\"min\":\"10\",\"max\":\"13\"}}],\"directRate\":\"20\",\"levelDiffRate\":[{\"level\":\"Vip0\",\"rate\":\"0\"},{\"level\":\"Vip1\",\"rate\":\"5\"},{\"level\":\"Vip2\",\"rate\":\"10\"},{\"level\":\"Vip3\",\"rate\":\"15\"},{\"level\":\"Vip4\",\"rate\":\"20\"},{\"level\":\"Vip5\",\"rate\":\"25\"},{\"level\":\"Vip6\",\"rate\":\"30\"},{\"level\":\"Vip7\",\"rate\":\"35\"},{\"level\":\"Vip8\",\"rate\":\"40\"},{\"level\":\"Vip9\",\"rate\":\"50\"}],\"sameLevelRate\":\"5\"}', 1741860801, 1741881600, 1, 'MEV', '2025-03-13 18:13:21', '2025-03-13 18:13:21');

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
  `direct_count` int(11) NULL DEFAULT 0 COMMENT '直推人数',
  `team_count` int(11) NULL DEFAULT 0 COMMENT '团队人数',
  `lang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '1',
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '最后登录IP',
  `last_login_at` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  UNIQUE INDEX `identity`(`identity`) USING BTREE,
  INDEX `inx_fields`(`id`, `pid`, `identity`, `status`, `level`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '1', 0, 1, 0, 9, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', '192.168.3.10', '2025-03-14 11:55:41', '2025-03-13 18:12:21', '2025-03-14 11:55:41');
INSERT INTO `users` VALUES (2, '2', 1, 1, 0, 9, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (3, '3', 2, 1, 0, 7, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (4, '4', 3, 1, 0, 6, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (5, '5', 4, 1, 0, 5, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (6, '6', 5, 1, 0, 4, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (7, '7', 6, 1, 0, 3, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (8, '8', 7, 1, 0, 2, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (9, '9', 8, 1, 0, 1, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', NULL, NULL, '2025-03-13 18:12:21', '2025-03-13 18:12:21');
INSERT INTO `users` VALUES (10, '10', 9, 1, 0, 0, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', '192.168.3.10', '2025-03-14 14:55:59', '2025-03-13 18:12:21', '2025-03-14 14:55:59');
INSERT INTO `users` VALUES (11, 'CaGTvRyDdohCZp2teEVws9Mu1NqVUeAwSrrsZ8ZGWoiC', 0, 0, 0, 0, '/images/avatars/avatar.png', '', 0, 0, 'zh_CN', '192.168.3.35', '2025-03-14 15:28:12', '2025-03-14 13:32:37', '2025-03-14 15:28:12');

-- ----------------------------
-- Table structure for withdraws
-- ----------------------------
DROP TABLE IF EXISTS `withdraws`;
CREATE TABLE `withdraws`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `coin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(20, 8) UNSIGNED NULL DEFAULT NULL,
  `fee` decimal(20, 8) NULL DEFAULT 0.00000000 COMMENT '手续费',
  `fee_rate` decimal(15, 8) NULL DEFAULT 0.00000000 COMMENT '费率',
  `user_wallet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '付款账户',
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '交易签名',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT 0,
  `datetime` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '玩家提现表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of withdraws
-- ----------------------------
INSERT INTO `withdraws` VALUES (1, 10, 'USDT', 100.00000000, 0.00000000, 0.00000000, '10', '66', '', 0, 1741918894, '2025-03-14 10:21:34', '2025-03-14 10:21:45');
INSERT INTO `withdraws` VALUES (2, 10, 'USDT', 100.00000000, 2.00000000, 0.02000000, '10', NULL, NULL, 0, 1741919366, '2025-03-14 10:29:26', '2025-03-14 10:29:26');
INSERT INTO `withdraws` VALUES (3, 10, 'USDT', 100.00000000, 2.00000000, 0.02000000, '10', NULL, NULL, 0, 1741919463, '2025-03-14 10:31:03', '2025-03-14 10:31:03');

SET FOREIGN_KEY_CHECKS = 1;
