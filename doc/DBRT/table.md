##Database Design

### 表设计
*`loading`*

### 数据库
> 数据库 -- `movie`
```sql
CREATE DATABASE `movie` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `movie`;
```
_ _ _

### 表结构
> #### 影院 *`pre_cinema`*
```sql
CREATE TABLE `pre_cinema` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT '0',
  `name` varchar(32) NOT NULL,
  `lng` float(9,6) unsigned NOT NULL COMMENT '经度',
  `lat` float(9,6) unsigned NOT NULL COMMENT '纬度',
  `address` varchar(50) NOT NULL,
  `gewara_id` int(10) unsigned NOT NULL COMMENT '格瓦拉平台影院主键ID',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_name` (`name`),
  KEY `idx_location` (`city_id`,`lng`,`lat`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='电影院';
```
_ _ _

> #### 电影 *`pre_movie`*
```sql
CREATE TABLE `pre_movie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `open_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上映日期',
  `score` float(3,1) unsigned DEFAULT '0.0' COMMENT '电影评分（豆瓣）',
  `gewara_id` int(11) unsigned DEFAULT '0' COMMENT '格瓦拉电影主键ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isvalid` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否有效',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='电影';
```
_ _ _

> #### 场次 *`pre_screen`*
```sql
CREATE TABLE `pre_screen` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `platform` tinyint(3) unsigned NOT NULL COMMENT '1-淘票票 2-格瓦拉 详见PlatformLiteral列表',
  `cinema_id` int(11) unsigned NOT NULL COMMENT '电影院ID，对应pre_cinema主键ID',
  `movie_id` int(11) unsigned NOT NULL COMMENT '电影ID，对应pre_cinema表主键ID',
  `screen_id` int(11) unsigned NOT NULL COMMENT '外部的场次ID(非内部)，每个平台对应的场次主键ID，主要用于做唯一约束',
  `room` varchar(20) NOT NULL DEFAULT '' COMMENT '场次厅号',
  `price` float(5,1) unsigned NOT NULL COMMENT '场次价格',
  `tag` varchar(20) DEFAULT '' COMMENT '放映室标签 如：杜比巨幕，IMAX...各平台抓取时有的话就插入，多标签之间用|分割',
  `open_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开映时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_cinema_movie_mp` (`cinema_id`,`movie_id`,`screen_id`) USING BTREE COMMENT '影院+电影+厅室唯一确定场次',
  KEY `idx_movie` (`movie_id`) USING BTREE,
  KEY `idx_open` (`open_time`)
) ENGINE=InnoDB AUTO_INCREMENT=653 DEFAULT CHARSET=utf8 COMMENT='电影场次';
```

<!-- 暂未用到的一些表

--
-- 表的结构 `pre_cinema_movie`
--

CREATE TABLE IF NOT EXISTS `pre_cinema_movie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie_id` int(11) NOT NULL,
  `cinema_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='cinema_movie中间表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pre_city`
--

CREATE TABLE IF NOT EXISTS `pre_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '父id',
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='省市县' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pre_price`
--

CREATE TABLE IF NOT EXISTS `pre_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie_id` int(11) NOT NULL COMMENT '电影id',
  `cinema_id` int(11) NOT NULL COMMENT '电影院id (空间换时间)',
  `screen_id` int(11) NOT NULL COMMENT '场次id',
  `platform_id` int(11) NOT NULL COMMENT '平台id',
  `price` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='票价' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pre_platform`
--

CREATE TABLE IF NOT EXISTS `pre_platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-->
