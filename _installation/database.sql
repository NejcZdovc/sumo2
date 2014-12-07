-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Sep 20, 2013 at 08:45 AM
-- Server version: 5.1.54
-- PHP Version: 5.4.16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `cms_article`
--

CREATE TABLE IF NOT EXISTS `cms_article` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL DEFAULT '1',
  `title` varchar(400) NOT NULL,
  `altPrefix` text NOT NULL,
  `stub` text NOT NULL,
  `content` text NOT NULL,
  `category` text NOT NULL,
  `dateStart` int(11) NOT NULL,
  `dateEnd` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `authorAlias` varchar(100) NOT NULL,
  `date` int(11) NOT NULL,
  `changes` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `image` int(11) NOT NULL,
  `galleryCat` int(5) NOT NULL,
  `lang` int(11) NOT NULL DEFAULT '1',
  `parent` int(5) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `keywords` text,
  `description` text,
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_article_categories`
--

CREATE TABLE IF NOT EXISTS `cms_article_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL DEFAULT '1',
  `title` varchar(100) NOT NULL,
  `altPrefix` text NOT NULL,
  `description` text NOT NULL,
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `lang` int(11) NOT NULL DEFAULT '1',
  `parent` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_article_images`
--

CREATE TABLE IF NOT EXISTS `cms_article_images` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `articleID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `file` varchar(200) NOT NULL,
  `statusID` enum('N','D') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`),
  KEY `articleID` (`articleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_article_tags`
--

CREATE TABLE IF NOT EXISTS `cms_article_tags` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `value` text NOT NULL,
  `articleID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `articleID` (`articleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_components_def`
--

CREATE TABLE IF NOT EXISTS `cms_components_def` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `tables` text NOT NULL,
  `componentName` varchar(200) NOT NULL,
  `version` varchar(45) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('N','D','DD') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_domains`
--

CREATE TABLE IF NOT EXISTS `cms_domains` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `parentID` int(11) DEFAULT NULL,
  `locator` tinyint(1) NOT NULL DEFAULT '0',
  `alias` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `parentID` (`parentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_domains_countries`
--

CREATE TABLE IF NOT EXISTS `cms_domains_countries` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domainID` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `domainID` (`domainID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_domains_ids`
--

CREATE TABLE IF NOT EXISTS `cms_domains_ids` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('com','mod','group','lang') NOT NULL,
  `elementID` int(11) DEFAULT NULL,
  `domainID` int(11) NOT NULL,
  `value` text,
  PRIMARY KEY (`ID`),
  KEY `elementID` (`elementID`),
  KEY `domainID` (`domainID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_domains_ips`
--

CREATE TABLE IF NOT EXISTS `cms_domains_ips` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domainID` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `domainID` (`domainID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_favorites`
--

CREATE TABLE IF NOT EXISTS `cms_favorites` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `option1` int(11) NOT NULL DEFAULT '0',
  `option2` int(11) NOT NULL DEFAULT '0',
  `option3` int(11) NOT NULL DEFAULT '0',
  `option4` int(11) NOT NULL DEFAULT '0',
  `option5` int(11) NOT NULL DEFAULT '0',
  `option6` int(11) NOT NULL DEFAULT '0',
  `option7` int(11) NOT NULL DEFAULT '0',
  `option8` int(11) NOT NULL DEFAULT '0',
  `option9` int(11) NOT NULL DEFAULT '0',
  `option10` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `cms_favorites_def`
--

CREATE TABLE IF NOT EXISTS `cms_favorites_def` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `img` varchar(300) NOT NULL,
  `click` varchar(100) NOT NULL,
  `comID` int(11) NOT NULL,
  `statusID` enum('N','D') NOT NULL DEFAULT 'N',
  `itemID` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21;

--
-- Dumping data for table `cms_favorites_def`
--

INSERT INTO `cms_favorites_def` VALUES 
(1,'FAV_SITE','FAV_SITE_2','background-image:url(images/css_sprite.png);background-position:-194px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_ftp\')',0,'N','a_ftp'),
(2,'FAV_SITE','FAV_SITE_5','background-image:url(images/css_sprite.png);background-position:-434px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_menus\')',0,'N','a_menus'),
(3,'FAV_SITE','FAV_SITE_3','background-image:url(images/css_sprite.png);background-position:-193px -1676px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_settings\')',0,'N','a_settings'),
(4,'FAV_SITE','FAV_SITE_4','background-image:url(images/css_sprite.png);background-position:-289px -1676px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_trash\')',0,'N','a_trash'),
(5,'FAV_USER','FAV_USER_1','background-image:url(images/css_sprite.png);background-position:-334px -1676px;height:48px;width:48px;','sumo2.dialog.NewDialog(\'d_user_add_user\')',0,'N','d_user_add_user'),
(6,'FAV_USER','FAV_USER_2','background-image:url(images/css_sprite.png);background-position:-382px -1676px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_user_view_u\')',0,'N','a_user_view_u'),
(7,'FAV_USER','FAV_USER_3','background-image:url(images/css_sprite.png);background-position:-241px -1628px;height:48px;width:48px;','sumo2.dialog.NewDialog(\'d_user_add_group\')',0,'N','d_user_add_group'),
(8,'FAV_USER','FAV_USER_4','background-image:url(images/css_sprite.png);background-position:-290px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_user_view_g\')',0,'N','a_user_view_g'),
(9,'FAV_MODULES','FAV_MODULES_2','background-image:url(images/css_sprite.png);background-position:-96px -1676px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_module_view\')',0,'N','a_module_view'),
(10,'FAV_MODULES','FAV_MODULES_3','background-image:url(images/css_sprite.png);background-position:-50px -1676px;height:48px;width:48px;','sumo2.dialog.NewDialog(\'d_module_install\')',0,'N','d_module_install'),
(11,'FAV_MAIL','FAV_MAIL_1','background-image:url(images/css_sprite.png);background-position:-385px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_mail_new\')',0,'N','a_mail_new'),
(12,'FAV_MAIL','FAV_MAIL_2','background-image:url(images/css_sprite.png);background-position:-337px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_mail_inbox\')',0,'N','a_mail_inbox'),
(13,'FAV_MAIL','FAV_MAIL_3','background-image:url(images/css_sprite.png);background-position:-145px -1676px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_mail_sent\')',0,'N','a_mail_sent'),
(14,'FAV_ARTICLES','FAV_ARTICLES_1','background-image:url(images/css_sprite.png);background-position:0px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_article_new_a\')',0,'N','a_article_new_a'),
(15,'FAV_ARTICLES','FAV_ARTICLES','background-image:url(images/css_sprite.png);background-position:-49px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_article_view_a\')',0,'N','a_article_view_a'),
(16,'FAV_ARTICLES','FAV_ARTICLES_2','background-image:url(images/css_sprite.png);background-position:-97px -1628px;height:48px;width:48px;','sumo2.dialog.NewDialog(\'d_article_new_c\')',0,'N','d_article_new_c'),
(17,'FAV_ARTICLES','FAV_ARTICLES_3','background-image:url(images/css_sprite.png);background-position:-143px -1628px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_article_view_c\')',0,'N','a_article_view_c'),
(18,'FAV_SITE','FAV_SITE_6','background-image:url(images/css_sprite.png);background-position:-241px -1676px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_sitetree\')',0,'N','a_sitetree'),
(19,'FAV_USER','FAV_USER_5','background-image:url(images/css_sprite.png);background-position:-434px -1676px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_user_view_f\')',0,'N','a_user_view_f'),
(20,'FAV_SITE','FAV_SITE_7','background-image:url(images/css_sprite.png);background-position:-701px -1708px;height:48px;width:48px;','sumo2.accordion.NewPanel(\'a_domains\')',0,'N','a_domains');

-- --------------------------------------------------------

--
-- Table structure for table `cms_global_settings`
--

CREATE TABLE IF NOT EXISTS `cms_global_settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL DEFAULT '1',
  `title` varchar(100) NOT NULL,
  `display_title` enum('D','F') NOT NULL DEFAULT 'D',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `template` int(10) NOT NULL,
  `offline` enum('Y','N') NOT NULL DEFAULT 'Y',
  `SEO` enum('Y','N') NOT NULL DEFAULT 'N',
  `front_lang` int(2) NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL,
  `GA_ID` varchar(50) NOT NULL,
  `GA_type` int(1) NOT NULL,
  `GA_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `WM_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `WM_ID` varchar(60) NOT NULL,
  `cacheNumber` int(11) NOT NULL DEFAULT '18536',
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_group_includes`
--

CREATE TABLE IF NOT EXISTS `cms_group_includes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(200) NOT NULL,
  `type` enum('css','javascript') NOT NULL,
  `modulID` int(11) NOT NULL,
  `md5` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_homepage`
--

CREATE TABLE IF NOT EXISTS `cms_homepage` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `altTitle` varchar(100) NOT NULL,
  `altPrefix` text NOT NULL,
  `lang` int(3) NOT NULL,
  `keyword` text NOT NULL,
  `description` text NOT NULL,
  `template` int(3) NOT NULL,
  `link` int(6) NOT NULL,
  `selection` int(2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_ip_locator`
--

CREATE TABLE IF NOT EXISTS `cms_ip_locator` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `IP` VARCHAR(70) NOT NULL,
  `country` TEXT NOT NULL,
  `countryCode` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_language`
--

CREATE TABLE IF NOT EXISTS `cms_language` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `short` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_language`
--

INSERT INTO `cms_language` (`ID`, `name`, `short`, `enabled`) VALUES
(1, 'English', 'en', 1),
(2, 'Slovenian', 'sl', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_language_front`
--

CREATE TABLE IF NOT EXISTS `cms_language_front` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_slovenian_ci NOT NULL,
  `short` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_mail_main`
--

CREATE TABLE IF NOT EXISTS `cms_mail_main` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `subject` text NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_slovenian_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_mail_sent`
--

CREATE TABLE IF NOT EXISTS `cms_mail_sent` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `recipientID` int(11) NOT NULL,
  `mainID` int(11) NOT NULL,
  `status` enum('O','C','D','DD') NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `recipientID` (`recipientID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_menus`
--

CREATE TABLE IF NOT EXISTS `cms_menus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `lang` int(1) NOT NULL DEFAULT '1',
  `parent` int(3) NOT NULL DEFAULT '0',
  `s_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_menus_items`
--

CREATE TABLE IF NOT EXISTS `cms_menus_items` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL,
  `menuID` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_slovenian_ci NOT NULL,
  `altPrefix` text NOT NULL,
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `parentID` int(11) NOT NULL DEFAULT '-1',
  `orderID` int(11) NOT NULL,
  `keyword` text NOT NULL,
  `description` text NOT NULL,
  `change` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `template` int(11) NOT NULL DEFAULT '1',
  `selection` int(2) NOT NULL DEFAULT '1',
  `link` varchar(200) NOT NULL,
  `alias` text NOT NULL,
  `target` int(11) NOT NULL DEFAULT '1',
  `restriction` int(1) NOT NULL DEFAULT '1',
  `showM` enum('Y','N') NOT NULL DEFAULT 'Y',
  `moduleID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`),
  KEY `menuID` (`menuID`),
  KEY `moduleID` (`moduleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_modules_def`
--

CREATE TABLE IF NOT EXISTS `cms_modules_def` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `moduleName` varchar(200) NOT NULL,
  `editName` varchar(200) NOT NULL,
  `componentID` int(11) NOT NULL,
  `tables` text NOT NULL,
  `editTable` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `version` varchar(45) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('N','D','DD') NOT NULL DEFAULT 'N',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_modul_prefix`
--

CREATE TABLE IF NOT EXISTS `cms_modul_prefix` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL DEFAULT '1',
  `name` varchar(100) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `prefix` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `cms_seo_redirects`
--

CREATE TABLE IF NOT EXISTS `cms_seo_redirects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `source` text NOT NULL,
  `destination` text NOT NULL,
  `type` varchar(100) NOT NULL,
  `domainID` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `domainID` (`domainID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_state`
--

CREATE TABLE IF NOT EXISTS `cms_state` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `state` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `cms_sumo_settings`
--

CREATE TABLE IF NOT EXISTS `cms_sumo_settings` (
  `ID` int(11) NOT NULL DEFAULT '1',
  `version` varchar(10) NOT NULL,
  `FTP_user` text,
  `FTP_pass` text,
  `FTP_url` text,
  `FTP_port` varchar(45) DEFAULT '21',
  `welcome` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cms_sumo_settings` (`ID`, `version`, `FTP_user`, `FTP_pass`, `FTP_url`, `FTP_port`, `welcome`) VALUES
(1, '1.36', NULL, NULL, NULL, '21', NULL);


-- --------------------------------------------------------

--
-- Table structure for table `cms_template`
--

CREATE TABLE IF NOT EXISTS `cms_template` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL DEFAULT '1',
  `name` varchar(100) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('N','D','DD') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_template_position`
--

CREATE TABLE IF NOT EXISTS `cms_template_position` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `domain` int(11) NOT NULL DEFAULT '1',
  `name` varchar(20) NOT NULL,
  `prefix` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_user`
--

CREATE TABLE IF NOT EXISTS `cms_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `pass` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `registration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visit` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`),
  KEY `GroupID` (`GroupID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_aditional`
--

CREATE TABLE IF NOT EXISTS `cms_user_aditional` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_fields`
--

CREATE TABLE IF NOT EXISTS `cms_user_fields` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `labelName` varchar(200) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `fieldId` varchar(200) DEFAULT NULL,
  `description` text,
  `type` int(11) NOT NULL DEFAULT '1',
  `required` int(1) NOT NULL DEFAULT '0',
  `min` int(5) NOT NULL DEFAULT '1',
  `max` int(5) NOT NULL DEFAULT '2',
  `extra` text,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_groups`
--

CREATE TABLE `cms_user_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `creation` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  `cache` tinyint(1) NOT NULL DEFAULT '1',
  `errorLog` tinyint(1) NOT NULL DEFAULT '1',
  `dataLog` tinyint(1) NOT NULL DEFAULT '1',
  `login` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `cms_user_groups`
--

INSERT INTO `cms_user_groups` VALUES 
(1,'Super administrator','Super administrators have all the necessary permissions to operate the CMS and all other backend services like server and database.','2010-07-16 12:08:31',1,'N',1,1,1,1),
(2,'Administrator','Administrators are the ones responsible so that the site runs uninterrupted and within the rules they create, they have all the necessary permissions.','2010-07-16 12:11:02',1,'N',1,1,1,0),
(3,'User','Users have the least permissions, they can only do the most basic operations and use only a few of the services the system offers.','2010-07-16 12:13:29',1,'N',1,1,1,0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_groups_permissions`
--

CREATE TABLE `cms_user_groups_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `groupID` int(11) NOT NULL,
  `objectID` varchar(200) NOT NULL,
  `permission` int(1) NOT NULL DEFAULT '1',
  `file` varchar(100) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `groupID` (`groupID`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `cms_user_groups_permissions`
--

INSERT INTO `cms_user_groups_permissions` VALUES 
(1,1,'a_welcome',5,'pages@welcome.php',1),
(2,1,'a_user_view_u',5,'pages@user.management.view.users.php',1),
(3,1,'a_user_view_g',5,'pages@user.management.view.groups.php',1),
(4,1,'a_user_group_vd',5,'pages@user.management.view.group.details.php',1),
(5,1,'a_user_edit_group',5,'pages@user.management.edit.group.php',1),
(6,1,'a_user_view_f',5,'pages@user.view.fields.php',1),
(7,1,'a_article_view_c',5,'pages@article.view.categories.php',1),
(8,1,'a_article_view_cd',5,'pages@article.view.categories.details.php',1),
(9,1,'a_article_view_a',5,'pages@article.view.articles.php',1),
(10,1,'a_article_new_a',5,'pages@article.new.article.php',1),
(11,1,'a_article_edit_a',5,'pages@article.edit.article.php',1),
(12,1,'a_article_a_translate',5,'pages@article.article.translate.php',1),
(13,1,'a_mail_inbox',0,'pages@mail.inbox.php',0),
(14,1,'a_mail_new',0,'pages@mail.new.mail.php',0),
(15,1,'a_mail_sent',0,'pages@mail.sent.items.php',0),
(16,1,'a_seo_redirect_view',5,'pages@seo.urls.view.php',1),
(17,1,'a_ftp',5,'pages@file.manager.php',1),
(18,1,'a_menus',5,'pages@menus.php',1),
(19,1,'a_settings',5,'pages@settings.php',1),
(20,1,'a_sitetree',5,'pages@site.tree.php',1),
(21,1,'a_trash',5,'pages@trash.php',1),
(22,1,'a_module_view',5,'pages@module.view.php',1),
(23,1,'a_domains',5,'pages@domains.php',1),
(24,1,'d_user_add_user',2,'pages@user.management.add.user.php',1),
(25,1,'d_user_edit_user',2,'pages@user.management.edit.user.php',1),
(26,1,'d_user_add_group',2,'pages@user.management.add.group.php',1),
(27,1,'d_user_add_fields',2,'pages@user.add.field.php',1),
(28,1,'d_user_edit_fields',2,'pages@user.edit.field.php',1),
(29,1,'d_article_new_c',2,'pages@article.new.category.php',1),
(30,1,'d_article_cat',2,'pages@article.select.category.php',1),
(31,1,'d_article_edit_c',2,'pages@article.edit.category.php',1),
(32,1,'d_article_c_translate',2,'pages@article.category.translate.php',1),
(33,1,'d_article_image_rename',2,'pages@article.image.rename.php',1),
(34,1,'d_ftp_file_rename',2,'pages@file.manager.rename.php',1),
(35,1,'d_ftp_folder_rename',2,'pages@file.manager.rename.php',1),
(36,1,'d_ftp_new_folder',2,'pages@file.manager.new.php',1),
(37,1,'d_ftp_upload',2,'pages@file.manager.upload.php',1),
(38,1,'d_menus_new_m',2,'pages@menus.new.php',1),
(39,1,'d_menus_edit_m',2,'pages@menus.edit.php',1),
(40,1,'d_menus_edit_h',2,'pages@menus.homepage.php',1),
(41,1,'d_menus_sitetree',2,'pages@menus.sitetree.php',1),
(42,1,'d_menus_new_i',2,'pages@menus.newitem.php',1),
(43,1,'d_menus_new_s',2,'pages@menus.newitem.special.php',1),
(44,1,'d_menus_edit_i',2,'pages@menus.edititem.php',1),
(45,1,'d_menues_trans',2,'pages@menus.translate.php',1),
(46,1,'d_menus_sitetree_trans',2,'pages@menus.sitetree.translate.php',1),
(47,1,'d_settings_add_t',2,'pages@settings.addtemplate.php',1),
(48,1,'d_settings_add_lf',2,'pages@settings.addlang.front.php',1),
(49,1,'d_settings_add_lb',2,'pages@settings.addlang.back.php',1),
(50,1,'d_settings_edit_t',2,'pages@settings.edittemplate.php',1),
(51,1,'a_settings_add_p',2,'pages@settings.prefix.php',1),
(52,1,'a_settings_add_tp',2,'pages@settings.position.php',1),
(53,1,'d_logo',2,'pages@settings.logo.php',1),
(54,1,'d_seo_redirect_add',2,'pages@seo.urls.add.php',1),
(55,1,'d_seo_redirect_edit',2,'pages@seo.urls.edit.php',1),
(56,1,'d_module_install',2,'pages@module.install.php',1),
(57,1,'d_module_edit',2,'pages@module.edit.php',1),
(58,1,'h_GA',2,'pages@help.ga.php',1),
(59,1,'d_site_tree_rename',2,'pages@site.tree.rename.php',1),
(60,1,'d_relogin',2,'pages@relogin.php',1),
(61,1,'d_update_text',2,'pages@update.warning.php',1),
(62,1,'d_out_of_date',2,'pages@out.of.date.php',1),
(63,1,'d_preview',2,'pages@preview.php',1),
(64,1,'d_layoutmodule',2,'pages@site.tree.module.php',1),
(65,1,'d_favorites',2,'pages@favorites.edit.php',1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_settings`
--

CREATE TABLE IF NOT EXISTS `cms_user_settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `items` int(3) NOT NULL DEFAULT '20',
  `accordion` int(2) NOT NULL DEFAULT '5',
  `preview` int(1) NOT NULL DEFAULT '1',
  `view` enum('L','T') NOT NULL DEFAULT 'L',
  `translate_state` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `translate_lang` int(1) NOT NULL DEFAULT '1',
  `updateOption` varchar(3) NOT NULL DEFAULT 'ON',
  `beta` tinyint(4) DEFAULT '0',
  `developer` tinyint(1) NOT NULL DEFAULT '0',
  `domain` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `includes`
--

CREATE TABLE IF NOT EXISTS `includes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `link` text NOT NULL,
  `type` enum('css','javascript') NOT NULL,
  `modulID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `modulID` (`modulID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
