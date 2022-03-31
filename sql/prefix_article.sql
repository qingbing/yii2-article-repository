
-- ----------------------------
--  Table structure for `{{%article_category}}`
-- ----------------------------
CREATE TABLE `{{%article_category}}` (
  `key` varchar(100) NOT NULL COMMENT '分类标识',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '启用状态',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`key`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章仓库分类';

-- ----------------------------
--  Table structure for `{{%article_repository}}`
-- ----------------------------
CREATE TABLE `{{%article_repository}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `key` varchar(100) NOT NULL COMMENT '分类标识',
  `unique_key` varchar(100) NOT NULL COMMENT '文件标识',
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `author` varchar(100)  NOT NULL DEFAULT '' COMMENT '显示的发布者',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '显示名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '文章简述',
  `tag` varchar(255) NOT NULL COMMENT '文章标签',
  `content` text COMMENT '文章内容',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key_title` (`key`,`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章列表';

