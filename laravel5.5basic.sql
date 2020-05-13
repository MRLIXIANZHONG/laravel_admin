/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 80012
Source Host           : localhost:3306
Source Database       : laravel5.5basic

Target Server Type    : MYSQL
Target Server Version : 80012
File Encoding         : 65001

Date: 2019-12-16 23:55:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for iprecordings
-- ----------------------------
DROP TABLE IF EXISTS `iprecordings`;
CREATE TABLE `iprecordings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(32) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `iptype` int(1) NOT NULL DEFAULT '1' COMMENT '是否限制访问 1/不限制/2限制',
  `atlastroute` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '最后访问路由',
  `accesspermission` int(1) NOT NULL DEFAULT '1' COMMENT '访问权限 1/前台 2/后台',
  `accessnum` int(11) NOT NULL DEFAULT '1' COMMENT '访问次数',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '最后访问时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '有值说明已删除/时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipweiyi` (`address`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='访问IP记录表';

-- ----------------------------
-- Records of iprecordings
-- ----------------------------
INSERT INTO `iprecordings` VALUES ('2', '127.0.0.1', '1', 'admin/permission/group/index', '2', '500', null, '2019-12-16 23:51:54', null);
INSERT INTO `iprecordings` VALUES ('1', '127.0.0.2', '1', 'admin/system-iprecord/index', '2', '275', null, '2019-12-08 21:41:29', null);
INSERT INTO `iprecordings` VALUES ('3', '127.0.0.3', '2', 'admin/system-iprecord/index', '2', '12', null, '2019-12-08 21:41:29', null);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户直接权限';

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户角色';

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES ('1', 'App\\Models\\User', '1');
INSERT INTO `model_has_roles` VALUES ('15', 'App\\Models\\User', '2');

-- ----------------------------
-- Table structure for navigations
-- ----------------------------
DROP TABLE IF EXISTS `navigations`;
CREATE TABLE `navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级id 0为顶级',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(50) DEFAULT NULL,
  `uri` varchar(191) DEFAULT '' COMMENT '暂未使用',
  `sequence` smallint(6) NOT NULL DEFAULT '0' COMMENT '序列',
  `permission_id` int(11) DEFAULT NULL COMMENT '权限(路由)表id',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '上次更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='导航';

-- ----------------------------
-- Records of navigations
-- ----------------------------
INSERT INTO `navigations` VALUES ('3', '0', '用户管理', null, '', '2', '72', '2019-11-21 11:21:57', '2019-11-22 06:37:55');
INSERT INTO `navigations` VALUES ('5', '0', '权限设置', null, '', '4', null, '2019-11-22 05:50:42', '2019-12-04 14:14:10');
INSERT INTO `navigations` VALUES ('6', '5', '权限管理', null, '', '1', '55', '2019-11-22 05:53:53', '2019-11-22 06:37:35');
INSERT INTO `navigations` VALUES ('7', '5', '权限组管理', null, '', '2', '79', '2019-11-22 05:54:13', '2019-11-22 05:54:13');
INSERT INTO `navigations` VALUES ('8', '5', '角色管理', null, '', '3', '67', '2019-11-22 05:54:31', '2019-11-22 05:54:31');
INSERT INTO `navigations` VALUES ('9', '5', '导航管理', null, '', '4', '84', '2019-11-22 05:54:46', '2019-11-22 07:44:17');
INSERT INTO `navigations` VALUES ('10', '0', '首页', null, '', '1', '53', '2019-11-22 07:47:58', null);
INSERT INTO `navigations` VALUES ('11', '0', '系统管理', null, '', '3', null, '2019-12-04 14:14:00', null);
INSERT INTO `navigations` VALUES ('12', '11', 'Redis缓存', null, '', '1', '91', '2019-12-04 14:14:46', null);
INSERT INTO `navigations` VALUES ('13', '11', '日志管理', null, '', '2', '92', '2019-12-04 14:30:06', null);
INSERT INTO `navigations` VALUES ('14', '11', 'IP访问记录', null, '', '3', '95', '2019-12-08 21:30:40', null);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pg_id` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequence` smallint(6) DEFAULT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限表';

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('81', 'admin/permission/role/binding', 'web', '2019-11-21 04:01:33', '2019-12-16 23:51:00', '22', '角色管理-角色绑定权限', null, null, null);
INSERT INTO `permissions` VALUES ('48', 'admin/login', 'web', '2019-11-21 02:55:58', '2019-11-21 03:30:29', '1', '基础权限-登录', null, null, null);
INSERT INTO `permissions` VALUES ('49', 'admin/quit', 'web', '2019-11-21 02:55:58', '2019-11-21 03:30:29', '1', '基础权限-退出', null, null, null);
INSERT INTO `permissions` VALUES ('50', 'admin/directQuit', 'web', '2019-11-21 02:55:58', '2019-11-21 03:30:29', '1', '基础权限-退出', null, null, null);
INSERT INTO `permissions` VALUES ('51', 'admin/reset_pwd', 'web', '2019-11-21 02:55:58', '2019-11-21 03:30:29', '1', '基础权限-重置密码请求', null, null, null);
INSERT INTO `permissions` VALUES ('52', 'admin/execute/reset_pwd', 'web', '2019-11-21 02:55:58', '2019-11-21 03:30:29', '1', '基础权限-重置密码', null, null, null);
INSERT INTO `permissions` VALUES ('53', 'admin', 'web', '2019-11-21 02:55:58', '2019-11-21 03:30:29', '1', '基础权限-首页', null, null, null);
INSERT INTO `permissions` VALUES ('54', 'admin/myself', 'web', '2019-11-21 02:55:58', '2019-11-21 03:30:29', '1', '基础权限-个人中心', null, null, null);
INSERT INTO `permissions` VALUES ('55', 'admin/permission/route/index', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:35', '20', '权限管理', null, null, null);
INSERT INTO `permissions` VALUES ('80', 'admin/permission/group/list', 'web', '2019-11-21 04:01:33', '2019-12-16 23:50:50', '21', '权限组管理-权限组列表', null, null, null);
INSERT INTO `permissions` VALUES ('57', 'admin/permission/route/create', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:35', '20', '权限管理-权限增加', null, null, null);
INSERT INTO `permissions` VALUES ('58', 'admin/permission/route/update', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:35', '20', '权限管理-权限修改', null, null, null);
INSERT INTO `permissions` VALUES ('59', 'admin/permission/route/delete', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:35', '20', '权限管理-权限删除', null, null, null);
INSERT INTO `permissions` VALUES ('78', 'admin/permission/route/check', 'web', '2019-11-21 04:01:33', '2019-12-16 23:50:35', '20', '权限管理-路由检测', null, null, null);
INSERT INTO `permissions` VALUES ('79', 'admin/permission/group/index', 'web', '2019-11-21 04:01:33', '2019-12-16 23:50:50', '21', '权限组管理', null, null, null);
INSERT INTO `permissions` VALUES ('62', 'admin/permission/group/create', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:50', '21', '权限组管理-权限组增加', null, null, null);
INSERT INTO `permissions` VALUES ('63', 'admin/permission/group/update', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:50', '21', '权限组管理-权限组修改', null, null, null);
INSERT INTO `permissions` VALUES ('64', 'admin/permission/group/delete', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:50', '21', '权限组管理-权限组删除', null, null, null);
INSERT INTO `permissions` VALUES ('65', 'admin/permission/group/moveout', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:50', '21', '权限组管理-权限组移除权限', null, null, null);
INSERT INTO `permissions` VALUES ('66', 'admin/permission/group/movein', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:50', '21', '权限组管理-权限组增加权限', null, null, null);
INSERT INTO `permissions` VALUES ('67', 'admin/permission/role/index', 'web', '2019-11-21 02:55:58', '2019-12-16 23:51:00', '22', '角色管理', null, null, null);
INSERT INTO `permissions` VALUES ('68', 'admin/permission/role/list', 'web', '2019-11-21 02:55:58', '2019-12-16 23:51:00', '22', '角色管理-角色列表', null, null, null);
INSERT INTO `permissions` VALUES ('69', 'admin/permission/role/create', 'web', '2019-11-21 02:55:58', '2019-12-16 23:51:00', '22', '角色管理-角色增加', null, null, null);
INSERT INTO `permissions` VALUES ('70', 'admin/permission/role/update', 'web', '2019-11-21 02:55:58', '2019-12-16 23:51:00', '22', '角色管理-角色修改', null, null, null);
INSERT INTO `permissions` VALUES ('71', 'admin/permission/role/delete', 'web', '2019-11-21 02:55:58', '2019-12-16 23:51:00', '22', '角色管理-角色删除', null, null, null);
INSERT INTO `permissions` VALUES ('72', 'admin/user/index', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:03', '18', '用户管理', null, null, null);
INSERT INTO `permissions` VALUES ('73', 'admin/user/create', 'web', '2019-11-21 02:55:58', '2019-12-16 23:50:03', '18', '用户管理-用户增加', null, null, null);
INSERT INTO `permissions` VALUES ('77', 'Custom_throw', 'web', '2019-11-21 04:01:33', '2019-11-21 07:20:53', '1', '基础权限-异常抛出', null, null, null);
INSERT INTO `permissions` VALUES ('76', 'admin/user/delete', 'web', '2019-11-21 03:14:01', '2019-12-16 23:50:03', '18', '用户管理-用户删除', null, null, null);
INSERT INTO `permissions` VALUES ('82', 'admin/permission/role/initialize', 'web', '2019-11-21 04:01:33', '2019-12-16 23:51:00', '22', '角色管理-超管权限初始化', null, null, null);
INSERT INTO `permissions` VALUES ('83', 'admin/user/update', 'web', '2019-11-21 04:01:33', '2019-12-16 23:50:03', '18', '用户管理-用户修改', null, null, '修改用户信息');
INSERT INTO `permissions` VALUES ('84', 'admin/permission/navigation/index', 'web', '2019-11-21 10:31:08', '2019-12-16 23:51:07', '23', '导航管理', null, null, null);
INSERT INTO `permissions` VALUES ('85', 'admin/permission/navigation/create', 'web', '2019-11-21 11:20:55', '2019-12-16 23:51:07', '23', '导航管理-导航增加', null, null, null);
INSERT INTO `permissions` VALUES ('86', 'admin/permission/navigation/update', 'web', '2019-11-22 05:52:48', '2019-12-16 23:51:07', '23', '导航管理-导航修改', null, null, null);
INSERT INTO `permissions` VALUES ('87', 'admin/permission/navigation/delete', 'web', '2019-11-22 05:52:48', '2019-12-16 23:51:07', '23', '导航管理-导航删除', null, null, null);
INSERT INTO `permissions` VALUES ('92', 'admin/system-log/index', 'web', '2019-12-04 14:13:45', '2019-12-16 23:50:17', '19', '系统管理-日志管理', null, null, null);
INSERT INTO `permissions` VALUES ('93', 'admin/system-log/show', 'web', '2019-12-04 14:13:45', '2019-12-16 23:50:17', '19', '系统管理-日志管理-日志详情', null, null, null);
INSERT INTO `permissions` VALUES ('91', 'admin/system-redis/renew', 'web', '2019-12-04 14:13:45', '2019-12-16 23:50:17', '19', '系统管理-redis缓存', null, null, null);
INSERT INTO `permissions` VALUES ('94', 'admin/edit_avatar', 'web', '2019-12-08 21:29:55', '2019-12-16 23:49:49', '1', '基础权限-头像修改', null, null, null);
INSERT INTO `permissions` VALUES ('95', 'admin/system-iprecord/index', 'web', '2019-12-08 21:29:55', '2019-12-16 23:50:17', '19', '系统管理-ip记录', null, null, null);
INSERT INTO `permissions` VALUES ('96', 'admin/system-iprecord/show', 'web', '2019-12-15 21:34:04', '2019-12-16 23:50:17', '19', '系统管理-ip记录-记录详情', null, null, null);
INSERT INTO `permissions` VALUES ('97', 'admin/system-iprecord/edit_type', 'web', '2019-12-15 21:34:04', '2019-12-16 23:50:17', '19', '系统管理-ip记录-修改状态', null, null, null);
INSERT INTO `permissions` VALUES ('98', 'admin/user/type/update', 'web', '2019-12-16 23:42:35', '2019-12-16 23:50:03', '18', '用户管理-修改状态', null, null, null);

-- ----------------------------
-- Table structure for permission_groups
-- ----------------------------
DROP TABLE IF EXISTS `permission_groups`;
CREATE TABLE `permission_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限组';

-- ----------------------------
-- Records of permission_groups
-- ----------------------------
INSERT INTO `permission_groups` VALUES ('20', '权限管理', '2019-12-16 23:48:32', '2019-12-16 23:48:32');
INSERT INTO `permission_groups` VALUES ('19', '系统管理', '2019-12-16 23:48:24', '2019-12-16 23:48:24');
INSERT INTO `permission_groups` VALUES ('1', '基本路由组', '2019-11-17 08:33:05', '2019-11-17 08:33:05');
INSERT INTO `permission_groups` VALUES ('18', '用户管理', '2019-12-16 23:48:09', '2019-12-16 23:48:09');
INSERT INTO `permission_groups` VALUES ('21', '权限组管理', '2019-12-16 23:48:40', '2019-12-16 23:48:40');
INSERT INTO `permission_groups` VALUES ('22', '角色管理', '2019-12-16 23:48:48', '2019-12-16 23:48:48');
INSERT INTO `permission_groups` VALUES ('23', '导航管理', '2019-12-16 23:49:13', '2019-12-16 23:49:13');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色表';

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', '超级管理员', 'web', '2019-11-20 13:44:56', '2019-11-20 13:44:56', '拥有所有权限的最高级管理');
INSERT INTO `roles` VALUES ('15', '普通用户管理员', 'web', '2019-11-21 07:55:36', '2019-11-21 07:55:36', null);

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色权限表';

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES ('48', '1');
INSERT INTO `role_has_permissions` VALUES ('48', '15');
INSERT INTO `role_has_permissions` VALUES ('49', '1');
INSERT INTO `role_has_permissions` VALUES ('49', '15');
INSERT INTO `role_has_permissions` VALUES ('50', '1');
INSERT INTO `role_has_permissions` VALUES ('50', '15');
INSERT INTO `role_has_permissions` VALUES ('51', '1');
INSERT INTO `role_has_permissions` VALUES ('51', '15');
INSERT INTO `role_has_permissions` VALUES ('52', '1');
INSERT INTO `role_has_permissions` VALUES ('52', '15');
INSERT INTO `role_has_permissions` VALUES ('53', '1');
INSERT INTO `role_has_permissions` VALUES ('53', '15');
INSERT INTO `role_has_permissions` VALUES ('54', '1');
INSERT INTO `role_has_permissions` VALUES ('54', '15');
INSERT INTO `role_has_permissions` VALUES ('72', '15');
INSERT INTO `role_has_permissions` VALUES ('73', '15');
INSERT INTO `role_has_permissions` VALUES ('76', '15');
INSERT INTO `role_has_permissions` VALUES ('77', '1');
INSERT INTO `role_has_permissions` VALUES ('77', '15');
INSERT INTO `role_has_permissions` VALUES ('83', '15');
INSERT INTO `role_has_permissions` VALUES ('94', '1');
INSERT INTO `role_has_permissions` VALUES ('94', '15');
INSERT INTO `role_has_permissions` VALUES ('98', '15');

-- ----------------------------
-- Table structure for systemlogs
-- ----------------------------
DROP TABLE IF EXISTS `systemlogs`;
CREATE TABLE `systemlogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `path` varchar(255) DEFAULT NULL COMMENT '路由',
  `method` varchar(255) DEFAULT NULL COMMENT '请求方式',
  `agent` text COMMENT '客户端信息',
  `ip` varchar(255) DEFAULT NULL COMMENT 'IP地址',
  `ip_info` varchar(255) DEFAULT NULL,
  `sql` text,
  `params` text COMMENT '参数',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COMMENT='系统日志';

-- ----------------------------
-- Records of systemlogs
-- ----------------------------
INSERT INTO `systemlogs` VALUES ('7', '0', 'admin/login', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'user=pearton%40126.com&password=123456', '2019-12-02 16:25:43', '2019-12-02 16:25:43');
INSERT INTO `systemlogs` VALUES ('8', '1', 'admin/user/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2&username=lixiaodong&name=%E6%9D%8E%E6%99%93&email=790125098%40qq.com&role=%E6%99%AE%E9%80%9A%E7%94%A8%E6%88%B7%E7%AE%A1%E7%90%86%E5%91%98', '2019-12-02 16:26:33', '2019-12-02 16:26:33');
INSERT INTO `systemlogs` VALUES ('9', '1', 'admin/permission/navigation/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'parent_id=0&name=%E7%B3%BB%E7%BB%9F%E7%AE%A1%E7%90%86&sequence=3', '2019-12-04 14:14:00', '2019-12-04 14:14:00');
INSERT INTO `systemlogs` VALUES ('10', '1', 'admin/permission/navigation/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=5&parent_id=0&name=%E6%9D%83%E9%99%90%E8%AE%BE%E7%BD%AE&sequence=3', '2019-12-04 14:14:05', '2019-12-04 14:14:05');
INSERT INTO `systemlogs` VALUES ('11', '1', 'admin/permission/navigation/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=5&parent_id=0&name=%E6%9D%83%E9%99%90%E8%AE%BE%E7%BD%AE&sequence=4', '2019-12-04 14:14:10', '2019-12-04 14:14:10');
INSERT INTO `systemlogs` VALUES ('12', '1', 'admin/permission/navigation/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'parent_id=11&name=Redis%E7%BC%93%E5%AD%98&permission_id=91&sequence=1', '2019-12-04 14:14:46', '2019-12-04 14:14:46');
INSERT INTO `systemlogs` VALUES ('13', '1', 'admin/system-redis/renew', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-04 14:28:42', '2019-12-04 14:28:42');
INSERT INTO `systemlogs` VALUES ('14', '1', 'admin/permission/navigation/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'parent_id=11&name=%E6%97%A5%E5%BF%97%E7%AE%A1%E7%90%86&permission_id=92&sequence=2', '2019-12-04 14:30:06', '2019-12-04 14:30:06');
INSERT INTO `systemlogs` VALUES ('15', '1', 'admin/system-redis/renew', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-04 14:50:21', '2019-12-04 14:50:21');
INSERT INTO `systemlogs` VALUES ('16', '1', 'admin/system-redis/renew', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-04 22:53:24', '2019-12-04 22:53:24');
INSERT INTO `systemlogs` VALUES ('17', '1', 'admin/system-redis/renew', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-04 23:00:46', '2019-12-04 23:00:46');
INSERT INTO `systemlogs` VALUES ('18', '0', 'admin/reset_pwd', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'email=pearton%40126.com', '2019-12-04 23:03:20', '2019-12-04 23:03:20');
INSERT INTO `systemlogs` VALUES ('19', '0', 'admin/login', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'user=pearton%40126.com&password=123456', '2019-12-04 23:03:31', '2019-12-04 23:03:31');
INSERT INTO `systemlogs` VALUES ('20', '1', 'admin/edit_avatar', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-08 20:24:01', '2019-12-08 20:24:01');
INSERT INTO `systemlogs` VALUES ('21', '1', 'admin/edit_avatar', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-08 20:24:21', '2019-12-08 20:24:21');
INSERT INTO `systemlogs` VALUES ('22', '1', 'admin/myself', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1&username=admin&name=%E8%B6%85%E7%AE%A1&email=pearton%40126.com', '2019-12-08 20:24:27', '2019-12-08 20:24:27');
INSERT INTO `systemlogs` VALUES ('23', '1', 'admin/myself', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1&username=admin&name=%E8%B6%85%E7%AE%A1&email=pearton%40126.com&pwd=123456&repwd=12345', '2019-12-08 20:24:33', '2019-12-08 20:24:33');
INSERT INTO `systemlogs` VALUES ('24', '1', 'admin/myself', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1&username=admin&name=%E8%B6%85%E7%AE%A1&email=pearton%40126.com&pwd=123&repwd=123', '2019-12-08 20:24:39', '2019-12-08 20:24:39');
INSERT INTO `systemlogs` VALUES ('25', '1', 'admin/myself', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1&username=admin&name=%E8%B6%85%E7%AE%A1&email=pearton%40126.com&pwd=123456&repwd=123456', '2019-12-08 20:24:43', '2019-12-08 20:24:43');
INSERT INTO `systemlogs` VALUES ('26', '0', 'admin/login', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'user=pearton%40126.com&password=123456', '2019-12-08 20:26:50', '2019-12-08 20:26:50');
INSERT INTO `systemlogs` VALUES ('27', '0', 'admin/login', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'user=pearton%40126.com&password=123456', '2019-12-08 20:38:21', '2019-12-08 20:38:21');
INSERT INTO `systemlogs` VALUES ('28', '1', 'admin/system-redis/renew', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-08 20:39:21', '2019-12-08 20:39:21');
INSERT INTO `systemlogs` VALUES ('29', '0', 'admin/reset_pwd', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'email=pearton%40126.com', '2019-12-08 20:51:56', '2019-12-08 20:51:56');
INSERT INTO `systemlogs` VALUES ('30', '0', 'admin/reset_pwd', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'email=pearton%40126.com', '2019-12-08 20:53:29', '2019-12-08 20:53:29');
INSERT INTO `systemlogs` VALUES ('31', '0', 'admin/reset_pwd', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'email=pearton%40126.com', '2019-12-08 20:55:36', '2019-12-08 20:55:36');
INSERT INTO `systemlogs` VALUES ('32', '0', 'admin/login', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'user=pearton%40126.com&password=123456', '2019-12-08 20:55:41', '2019-12-08 20:55:41');
INSERT INTO `systemlogs` VALUES ('33', '1', 'admin/permission/navigation/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'parent_id=11&name=IP%E8%AE%BF%E9%97%AE%E8%AE%B0%E5%BD%95&permission_id=95&sequence=3', '2019-12-08 21:30:40', '2019-12-08 21:30:40');
INSERT INTO `systemlogs` VALUES ('34', '1', 'admin/system-iprecord/edit_type', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=3', '2019-12-08 22:03:13', '2019-12-08 22:03:13');
INSERT INTO `systemlogs` VALUES ('35', '1', 'admin/system-iprecord/edit_type', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1', '2019-12-08 22:03:18', '2019-12-08 22:03:18');
INSERT INTO `systemlogs` VALUES ('36', '1', 'admin/system-iprecord/edit_type', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1', '2019-12-08 22:05:27', '2019-12-08 22:05:27');
INSERT INTO `systemlogs` VALUES ('37', '1', 'admin/system-iprecord/edit_type', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=3', '2019-12-08 22:09:09', '2019-12-08 22:09:09');
INSERT INTO `systemlogs` VALUES ('38', '1', 'admin/system-iprecord/edit_type', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-08 22:09:18', '2019-12-08 22:09:18');
INSERT INTO `systemlogs` VALUES ('39', '1', 'admin/system-redis/renew', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', '', '2019-12-08 22:13:15', '2019-12-08 22:13:15');
INSERT INTO `systemlogs` VALUES ('40', '1', 'admin/permission/role/binding', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'permmissionArr%5B0%5D=admin%2Fuser%2Findex&permmissionArr%5B1%5D=admin%2Fuser%2Fcreate&permmissionArr%5B2%5D=admin%2Fuser%2Fdelete&permmissionArr%5B3%5D=admin%2Fuser%2Fupdate&permmissionArr%5B4%5D=admin%2Fpermission%2Froute%2Fcreate&id=15', '2019-12-16 22:39:43', '2019-12-16 22:39:43');
INSERT INTO `systemlogs` VALUES ('41', '1', 'admin/permission/role/binding', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'permmissionArr%5B0%5D=admin%2Fuser%2Findex&permmissionArr%5B1%5D=admin%2Fuser%2Fcreate&permmissionArr%5B2%5D=admin%2Fuser%2Fdelete&permmissionArr%5B3%5D=admin%2Fuser%2Fupdate&permmissionArr%5B4%5D=admin%2Fpermission%2Frole%2Fdelete&id=15', '2019-12-16 22:40:05', '2019-12-16 22:40:05');
INSERT INTO `systemlogs` VALUES ('42', '1', 'admin/permission/role/binding', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'permmissionArr%5B0%5D=admin%2Fuser%2Findex&permmissionArr%5B1%5D=admin%2Fuser%2Fcreate&permmissionArr%5B2%5D=admin%2Fuser%2Fdelete&permmissionArr%5B3%5D=admin%2Fuser%2Fupdate&permmissionArr%5B4%5D=admin%2Fpermission%2Frole%2Fdelete&id=15', '2019-12-16 22:40:12', '2019-12-16 22:40:12');
INSERT INTO `systemlogs` VALUES ('43', '1', 'admin/permission/role/binding', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'permmissionArr%5B0%5D=admin%2Fuser%2Findex&permmissionArr%5B1%5D=admin%2Fuser%2Fcreate&permmissionArr%5B2%5D=admin%2Fuser%2Fdelete&permmissionArr%5B3%5D=admin%2Fuser%2Fupdate&permmissionArr%5B4%5D=admin%2Fpermission%2Fgroup%2Fcreate&permmissionArr%5B5%5D=admin%2Fpermission%2Fgroup%2Fupdate&permmissionArr%5B6%5D=admin%2Fpermission%2Fgroup%2Fdelete&permmissionArr%5B7%5D=admin%2Fpermission%2Fgroup%2Fmoveout&permmissionArr%5B8%5D=admin%2Fpermission%2Fgroup%2Fmovein&permmissionArr%5B9%5D=admin%2Fpermission%2Fgroup%2Findex&permmissionArr%5B10%5D=admin%2Fpermission%2Fgroup%2Flist&id=15', '2019-12-16 22:40:27', '2019-12-16 22:40:27');
INSERT INTO `systemlogs` VALUES ('44', '1', 'admin/permission/role/binding', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'permmissionArr%5B0%5D=admin%2Fuser%2Findex&permmissionArr%5B1%5D=admin%2Fuser%2Fcreate&permmissionArr%5B2%5D=admin%2Fuser%2Fdelete&permmissionArr%5B3%5D=admin%2Fuser%2Fupdate&permmissionArr%5B4%5D=admin%2Fpermission%2Fgroup%2Fcreate&permmissionArr%5B5%5D=admin%2Fpermission%2Fgroup%2Fupdate&permmissionArr%5B6%5D=admin%2Fpermission%2Fgroup%2Fdelete&permmissionArr%5B7%5D=admin%2Fpermission%2Fgroup%2Fmoveout&permmissionArr%5B8%5D=admin%2Fpermission%2Fgroup%2Fmovein&permmissionArr%5B9%5D=admin%2Fpermission%2Fgroup%2Findex&permmissionArr%5B10%5D=admin%2Fpermission%2Fgroup%2Flist&permmissionArr%5B11%5D=admin%2Fpermission%2Frole%2Findex&permmissionArr%5B12%5D=admin%2Fpermission%2Frole%2Flist&permmissionArr%5B13%5D=admin%2Fpermission%2Frole%2Fcreate&permmissionArr%5B14%5D=admin%2Fpermission%2Frole%2Fupdate&permmissionArr%5B15%5D=admin%2Fpermission%2Frole%2Fdelete&permmissionArr%5B16%5D=admin%2Fpermission%2Frole%2Fbinding&permmissionArr%5B17%5D=admin%2Fpermission%2Frole%2Finitialize&id=15', '2019-12-16 22:40:58', '2019-12-16 22:40:58');
INSERT INTO `systemlogs` VALUES ('45', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:49:11', '2019-12-16 22:49:11');
INSERT INTO `systemlogs` VALUES ('46', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1', '2019-12-16 22:49:16', '2019-12-16 22:49:16');
INSERT INTO `systemlogs` VALUES ('47', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:51:58', '2019-12-16 22:51:58');
INSERT INTO `systemlogs` VALUES ('48', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1', '2019-12-16 22:52:14', '2019-12-16 22:52:14');
INSERT INTO `systemlogs` VALUES ('49', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:53:03', '2019-12-16 22:53:03');
INSERT INTO `systemlogs` VALUES ('50', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:54:05', '2019-12-16 22:54:05');
INSERT INTO `systemlogs` VALUES ('51', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:55:02', '2019-12-16 22:55:02');
INSERT INTO `systemlogs` VALUES ('52', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:55:17', '2019-12-16 22:55:17');
INSERT INTO `systemlogs` VALUES ('53', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:55:22', '2019-12-16 22:55:22');
INSERT INTO `systemlogs` VALUES ('54', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:58:14', '2019-12-16 22:58:14');
INSERT INTO `systemlogs` VALUES ('55', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=2', '2019-12-16 22:58:17', '2019-12-16 22:58:17');
INSERT INTO `systemlogs` VALUES ('56', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1', '2019-12-16 22:58:21', '2019-12-16 22:58:21');
INSERT INTO `systemlogs` VALUES ('57', '1', 'admin/user/type/update', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=1', '2019-12-16 22:58:24', '2019-12-16 22:58:24');
INSERT INTO `systemlogs` VALUES ('58', '1', 'admin/permission/group/delete', 'DELETE', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=12', '2019-12-16 23:09:41', '2019-12-16 23:09:41');
INSERT INTO `systemlogs` VALUES ('59', '1', 'admin/permission/group/delete', 'DELETE', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=12', '2019-12-16 23:12:13', '2019-12-16 23:12:13');
INSERT INTO `systemlogs` VALUES ('60', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E7%94%A8%E6%88%B7%E7%AE%A1%E7%90%86', '2019-12-16 23:12:35', '2019-12-16 23:12:35');
INSERT INTO `systemlogs` VALUES ('61', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=73%2C76%2C72%2C83&groupId=16', '2019-12-16 23:12:52', '2019-12-16 23:12:52');
INSERT INTO `systemlogs` VALUES ('62', '1', 'admin/permission/role/binding', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'permmissionArr%5B0%5D=admin%2Fpermission%2Fgroup%2Fcreate&permmissionArr%5B1%5D=admin%2Fpermission%2Fgroup%2Fupdate&permmissionArr%5B2%5D=admin%2Fpermission%2Fgroup%2Fdelete&permmissionArr%5B3%5D=admin%2Fpermission%2Fgroup%2Fmoveout&permmissionArr%5B4%5D=admin%2Fpermission%2Fgroup%2Fmovein&permmissionArr%5B5%5D=admin%2Fpermission%2Fgroup%2Findex&permmissionArr%5B6%5D=admin%2Fpermission%2Fgroup%2Flist&permmissionArr%5B7%5D=admin%2Fpermission%2Frole%2Findex&permmissionArr%5B8%5D=admin%2Fpermission%2Frole%2Flist&permmissionArr%5B9%5D=admin%2Fpermission%2Frole%2Fcreate&permmissionArr%5B10%5D=admin%2Fpermission%2Frole%2Fupdate&permmissionArr%5B11%5D=admin%2Fpermission%2Frole%2Fdelete&permmissionArr%5B12%5D=admin%2Fpermission%2Frole%2Fbinding&permmissionArr%5B13%5D=admin%2Fpermission%2Frole%2Finitialize&id=15', '2019-12-16 23:13:34', '2019-12-16 23:13:34');
INSERT INTO `systemlogs` VALUES ('63', '1', 'admin/permission/group/moveout', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=76%2C83', '2019-12-16 23:13:50', '2019-12-16 23:13:50');
INSERT INTO `systemlogs` VALUES ('64', '1', 'admin/permission/group/moveout', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=52%2C53', '2019-12-16 23:16:00', '2019-12-16 23:16:00');
INSERT INTO `systemlogs` VALUES ('65', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94%2C85%2C87%2C84%2C86%2C97%2C95%2C96%2C92%2C93%2C91&groupId=1', '2019-12-16 23:19:18', '2019-12-16 23:19:18');
INSERT INTO `systemlogs` VALUES ('66', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94&groupId=1', '2019-12-16 23:19:50', '2019-12-16 23:19:50');
INSERT INTO `systemlogs` VALUES ('67', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94&groupId=1', '2019-12-16 23:21:16', '2019-12-16 23:21:16');
INSERT INTO `systemlogs` VALUES ('68', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=85&groupId=1', '2019-12-16 23:22:58', '2019-12-16 23:22:58');
INSERT INTO `systemlogs` VALUES ('69', '1', 'admin/permission/group/moveout', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=85%2C94', '2019-12-16 23:24:32', '2019-12-16 23:24:32');
INSERT INTO `systemlogs` VALUES ('70', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94&groupId=1', '2019-12-16 23:25:04', '2019-12-16 23:25:04');
INSERT INTO `systemlogs` VALUES ('71', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94&groupId=1', '2019-12-16 23:26:27', '2019-12-16 23:26:27');
INSERT INTO `systemlogs` VALUES ('72', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94%2C85&groupId=1', '2019-12-16 23:35:09', '2019-12-16 23:35:09');
INSERT INTO `systemlogs` VALUES ('73', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94%2C85&groupId=1', '2019-12-16 23:35:41', '2019-12-16 23:35:41');
INSERT INTO `systemlogs` VALUES ('74', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94%2C85&groupId=1', '2019-12-16 23:36:08', '2019-12-16 23:36:08');
INSERT INTO `systemlogs` VALUES ('75', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94%2C85&groupId=1', '2019-12-16 23:37:29', '2019-12-16 23:37:29');
INSERT INTO `systemlogs` VALUES ('76', '1', 'admin/permission/group/moveout', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=85%2C94', '2019-12-16 23:38:01', '2019-12-16 23:38:01');
INSERT INTO `systemlogs` VALUES ('77', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94%2C85&groupId=1', '2019-12-16 23:38:40', '2019-12-16 23:38:40');
INSERT INTO `systemlogs` VALUES ('78', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=87%2C84%2C86&groupId=1', '2019-12-16 23:38:57', '2019-12-16 23:38:57');
INSERT INTO `systemlogs` VALUES ('79', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=87%2C84%2C86&groupId=1', '2019-12-16 23:39:41', '2019-12-16 23:39:41');
INSERT INTO `systemlogs` VALUES ('80', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=87%2C84%2C86&groupId=1', '2019-12-16 23:40:11', '2019-12-16 23:40:11');
INSERT INTO `systemlogs` VALUES ('81', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E5%AF%BC%E8%88%AA%E7%AE%A1%E7%90%86', '2019-12-16 23:42:19', '2019-12-16 23:42:19');
INSERT INTO `systemlogs` VALUES ('82', '1', 'admin/permission/group/moveout', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=84%2C85%2C86%2C87%2C94', '2019-12-16 23:43:08', '2019-12-16 23:43:08');
INSERT INTO `systemlogs` VALUES ('83', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94%2C85%2C87&groupId=1', '2019-12-16 23:43:31', '2019-12-16 23:43:31');
INSERT INTO `systemlogs` VALUES ('84', '1', 'admin/permission/group/moveout', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=85%2C87%2C94', '2019-12-16 23:43:56', '2019-12-16 23:43:56');
INSERT INTO `systemlogs` VALUES ('85', '1', 'admin/permission/group/delete', 'DELETE', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=13', '2019-12-16 23:44:21', '2019-12-16 23:44:21');
INSERT INTO `systemlogs` VALUES ('86', '1', 'admin/permission/group/delete', 'DELETE', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=14', '2019-12-16 23:44:26', '2019-12-16 23:44:26');
INSERT INTO `systemlogs` VALUES ('87', '1', 'admin/permission/group/delete', 'DELETE', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=17', '2019-12-16 23:44:30', '2019-12-16 23:44:30');
INSERT INTO `systemlogs` VALUES ('88', '1', 'admin/permission/group/delete', 'DELETE', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=16', '2019-12-16 23:44:34', '2019-12-16 23:44:34');
INSERT INTO `systemlogs` VALUES ('89', '1', 'admin/permission/group/delete', 'DELETE', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'id=15', '2019-12-16 23:44:38', '2019-12-16 23:44:38');
INSERT INTO `systemlogs` VALUES ('90', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E7%94%A8%E6%88%B7%E7%AE%A1%E7%90%86', '2019-12-16 23:48:09', '2019-12-16 23:48:09');
INSERT INTO `systemlogs` VALUES ('91', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E7%B3%BB%E7%BB%9F%E7%AE%A1%E7%90%86', '2019-12-16 23:48:24', '2019-12-16 23:48:24');
INSERT INTO `systemlogs` VALUES ('92', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E6%9D%83%E9%99%90%E7%AE%A1%E7%90%86', '2019-12-16 23:48:32', '2019-12-16 23:48:32');
INSERT INTO `systemlogs` VALUES ('93', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E6%9D%83%E9%99%90%E7%BB%84%E7%AE%A1%E7%90%86', '2019-12-16 23:48:40', '2019-12-16 23:48:40');
INSERT INTO `systemlogs` VALUES ('94', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E8%A7%92%E8%89%B2%E7%AE%A1%E7%90%86', '2019-12-16 23:48:48', '2019-12-16 23:48:48');
INSERT INTO `systemlogs` VALUES ('95', '1', 'admin/permission/group/create', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'name=%E5%AF%BC%E8%88%AA%E7%AE%A1%E7%90%86', '2019-12-16 23:49:13', '2019-12-16 23:49:13');
INSERT INTO `systemlogs` VALUES ('96', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=94&groupId=1', '2019-12-16 23:49:49', '2019-12-16 23:49:49');
INSERT INTO `systemlogs` VALUES ('97', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=73%2C76%2C72%2C98%2C83&groupId=18', '2019-12-16 23:50:03', '2019-12-16 23:50:03');
INSERT INTO `systemlogs` VALUES ('98', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=97%2C95%2C96%2C92%2C93%2C91&groupId=19', '2019-12-16 23:50:17', '2019-12-16 23:50:17');
INSERT INTO `systemlogs` VALUES ('99', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=78%2C57%2C59%2C55%2C58&groupId=20', '2019-12-16 23:50:35', '2019-12-16 23:50:35');
INSERT INTO `systemlogs` VALUES ('100', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=62%2C64%2C79%2C80%2C66%2C65%2C63&groupId=21', '2019-12-16 23:50:50', '2019-12-16 23:50:50');
INSERT INTO `systemlogs` VALUES ('101', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=81%2C69%2C71%2C67%2C82%2C68%2C70&groupId=22', '2019-12-16 23:51:00', '2019-12-16 23:51:00');
INSERT INTO `systemlogs` VALUES ('102', '1', 'admin/permission/group/movein', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'idArr=85%2C87%2C84%2C86&groupId=23', '2019-12-16 23:51:07', '2019-12-16 23:51:07');
INSERT INTO `systemlogs` VALUES ('103', '1', 'admin/permission/role/binding', 'PUT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '127.0.0.1', '本机地址', '', 'permmissionArr%5B0%5D=admin%2Fuser%2Findex&permmissionArr%5B1%5D=admin%2Fuser%2Fcreate&permmissionArr%5B2%5D=admin%2Fuser%2Fdelete&permmissionArr%5B3%5D=admin%2Fuser%2Fupdate&permmissionArr%5B4%5D=admin%2Fuser%2Ftype%2Fupdate&id=15', '2019-12-16 23:51:26', '2019-12-16 23:51:26');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` text NOT NULL COMMENT '密码',
  `email` varchar(255) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '姓名',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '头像地址',
  `login_token` varchar(32) NOT NULL DEFAULT '' COMMENT '登录令牌',
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Token',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '启用 1/启用 2/禁用',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '有值说明已删除/时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'eyJpdiI6InE4K0x2MVBaYjBXelhjYk1OcVBYcVE9PSIsInZhbHVlIjoiZE5qUTJEZ1pZeitEd3NxVVZmaWhMZz09IiwibWFjIjoiYzkwZmZiNGQ1NjJlYmJhOGJmOWNmMDdlMTY0MmIyOWMxNWU0OWQxNmQyMDkzMWZlMjM5ZDRjYWUxMWUyZjVkMiJ9', 'pearton@126.com', '超管', 'uploads/20191208/c49372c0fe3f321390a4d176610e25080eaa3d99.jpg', '059b3e5fe1063c6a1b7030045cc9aa0c', 'm2V7oSCl8WWbJfnFgVTrky5xU5H0GDDxTMU30Gz4g65r98UTseSm149lhOBu', '1', null, null, '2019-12-08 20:24:43');
INSERT INTO `users` VALUES ('2', 'lixiaodong', 'eyJpdiI6Im96dWFub1NCV1o2dVpxY0o3REN1Y0E9PSIsInZhbHVlIjoiN3VNKyswUmwxXC9IMnJxZE9FdTFaOXc9PSIsIm1hYyI6IjkxNWY3MjAxMGY2ZjFjMTIzMTJhNDA5M2NmMmFlYzVkMTA2NGI1ZjIzMzU2YzhmMGU0MDEwOTY2N2MyZjU3MWIifQ==', '790125098@qq.com', '李晓', null, 'bf1b6dd8dda66f3ec16d9ae3a81dc9b2', null, '1', null, null, '2019-12-02 16:26:33');
