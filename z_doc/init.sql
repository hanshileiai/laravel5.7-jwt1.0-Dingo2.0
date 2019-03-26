-- users
CREATE TABLE `db_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `user_pseudo_id` varchar(255) NOT NULL COMMENT 'firebase user_pseudo_id',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uuid` (`uuid`) USING BTREE,
  KEY `user_pseudo_id` (`user_pseudo_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- version
CREATE TABLE `db_versions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_type` enum('Android','iOS','') DEFAULT '',
  `version_name` varchar(255) DEFAULT '' COMMENT '应用的版本名（字符串版本号）',
  `version_code` int(10) DEFAULT '0' COMMENT '应用的版本号（商店版本号id）',
  `app_url` varchar(255) DEFAULT '' COMMENT 'app 下载地址',
  `description` text COMMENT '更新说明',
  `pop_status` tinyint(2) DEFAULT '0' COMMENT 'App 弹窗状态（0=>不显示弹窗，1=>显示弹窗）',
  `force_update` tinyint(2) DEFAULT '0' COMMENT '是否强制更新（0=>不强制，1=>强制）',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `os_type` (`os_type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;