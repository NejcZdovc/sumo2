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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


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
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `cms_favorites_def`
--

INSERT INTO `cms_favorites_def` (`ID`, `title`, `subtitle`, `img`, `click`, `comID`, `statusID`) VALUES
(1, 'FAV_SITE', 'FAV_SITE_2', 'background-image:url(images/css_sprite.png);background-position:-194px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_ftp'')', 0, 'N'),
(2, 'FAV_SITE', 'FAV_SITE_5', 'background-image:url(images/css_sprite.png);background-position:-434px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_menus'')', 0, 'N'),
(3, 'FAV_SITE', 'FAV_SITE_3', 'background-image:url(images/css_sprite.png);background-position:-193px -1676px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_settings'')', 0, 'N'),
(4, 'FAV_SITE', 'FAV_SITE_4', 'background-image:url(images/css_sprite.png);background-position:-289px -1676px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_trash'')', 0, 'N'),
(5, 'FAV_USER', 'FAV_USER_1', 'background-image:url(images/css_sprite.png);background-position:-334px -1676px;height:48px;width:48px;', 'sumo2.dialog.NewDialog(''d_user_add_user'')', 0, 'N'),
(6, 'FAV_USER', 'FAV_USER_2', 'background-image:url(images/css_sprite.png);background-position:-382px -1676px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_user_view_u'')', 0, 'N'),
(7, 'FAV_USER', 'FAV_USER_3', 'background-image:url(images/css_sprite.png);background-position:-241px -1628px;height:48px;width:48px;', 'sumo2.dialog.NewDialog(''d_user_add_group'')', 0, 'N'),
(8, 'FAV_USER', 'FAV_USER_4', 'background-image:url(images/css_sprite.png);background-position:-290px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_user_view_g'')', 0, 'N'),
(9, 'FAV_MODULES', 'FAV_MODULES_2', 'background-image:url(images/css_sprite.png);background-position:-96px -1676px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_module_view'')', 0, 'N'),
(10, 'FAV_MODULES', 'FAV_MODULES_3', 'background-image:url(images/css_sprite.png);background-position:-50px -1676px;height:48px;width:48px;', 'sumo2.dialog.NewDialog(''d_module_install'')', 0, 'N'),
(11, 'FAV_MAIL', 'FAV_MAIL_1', 'background-image:url(images/css_sprite.png);background-position:-385px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_mail_new'')', 0, 'N'),
(12, 'FAV_MAIL', 'FAV_MAIL_2', 'background-image:url(images/css_sprite.png);background-position:-337px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_mail_inbox'')', 0, 'N'),
(13, 'FAV_MAIL', 'FAV_MAIL_3', 'background-image:url(images/css_sprite.png);background-position:-145px -1676px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_mail_sent'')', 0, 'N'),
(14, 'FAV_ARTICLES', 'FAV_ARTICLES_1', 'background-image:url(images/css_sprite.png);background-position:0px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_article_new_a'')', 0, 'N'),
(15, 'FAV_ARTICLES', 'FAV_ARTICLES', 'background-image:url(images/css_sprite.png);background-position:-49px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_article_view_a'')', 0, 'N'),
(16, 'FAV_ARTICLES', 'FAV_ARTICLES_2', 'background-image:url(images/css_sprite.png);background-position:-97px -1628px;height:48px;width:48px;', 'sumo2.dialog.NewDialog(''d_article_new_c'')', 0, 'N'),
(17, 'FAV_ARTICLES', 'FAV_ARTICLES_3', 'background-image:url(images/css_sprite.png);background-position:-143px -1628px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_article_view_c'')', 0, 'N'),
(18, 'FAV_SITE', 'FAV_SITE_6', 'background-image:url(images/css_sprite.png);background-position:-241px -1676px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_sitetree'')', 0, 'N'),
(19, 'FAV_USER', 'FAV_USER_5', 'background-image:url(images/css_sprite.png);background-position:-434px -1676px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_user_view_f'')', 0, 'N'),
(20, 'FAV_SITE', 'FAV_SITE_7', 'background-image:url(images/css_sprite.png);background-position:-701px -1708px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_domains'')', 0, 'N'),
(21, 'FAV_SITE', 'FAV_SITE_8', 'background-image:url(images/css_sprite.png);background-position:-746px -1708px;height:48px;width:48px;', 'sumo2.accordion.NewPanel(''a_seo_redirect_view'')', 0, 'N');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


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
(1, '1.34', NULL, NULL, NULL, '21', NULL);


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
  `visit` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`),
  KEY `GroupID` (`GroupID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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

CREATE TABLE IF NOT EXISTS `cms_user_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `access` text NOT NULL,
  `creation` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('D','N','DD') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cms_user_groups`
--

INSERT INTO `cms_user_groups` (`ID`, `title`, `description`, `access`, `creation`, `enabled`, `status`) VALUES
(1, 'Super administrator', 'Super administrators have all the necessary permissions to operate the CMS and all other backend services like server and database.', 'a%3A26%3A%7Bs%3A10%3A%22FAV_SITE_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_5%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_4%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_USER_1%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_USER_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_USER_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_USER_4%22%3Bs%3A1%3A%225%22%3Bs%3A13%3A%22FAV_MODULES_2%22%3Bs%3A1%3A%225%22%3Bs%3A13%3A%22FAV_MODULES_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_1%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_3%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_1%22%3Bs%3A1%3A%225%22%3Bs%3A12%3A%22FAV_ARTICLES%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_2%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_6%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_USER_5%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_7%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SHOP_1%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SHOP_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SHOP_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SHOP_4%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SHOP_5%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SHOP_6%22%3Bs%3A1%3A%225%22%3B%7D', '2010-07-16 12:08:31', 1, 'N'),
(2, 'Administrator', 'Administrators are the ones responsible so that the site runs uninterrupted and within the rules they create, they have all the necessary permissions.', 'a%3A18%3A%7Bs%3A10%3A%22FAV_SITE_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_5%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_3%22%3Bs%3A1%3A%221%22%3Bs%3A10%3A%22FAV_SITE_4%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_1%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_3%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_1%22%3Bs%3A1%3A%225%22%3Bs%3A12%3A%22FAV_ARTICLES%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_2%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_SITE_6%22%3Bs%3A1%3A%223%22%3Bs%3A10%3A%22FAV_GALL_1%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_GALL_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_GALL_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_GALL_4%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_GALL_5%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_GALL_6%22%3Bs%3A1%3A%225%22%3B%7D', '2010-07-16 12:11:02', 1, 'N'),
(3, 'User', 'Users have the least permissions, they can only do the most basic operations and use only a few of the services the system offers.', 'a%3A14%3A%7Bs%3A10%3A%22FAV_USER_1%22%3Bs%3A1%3A%221%22%3Bs%3A10%3A%22FAV_USER_2%22%3Bs%3A1%3A%221%22%3Bs%3A10%3A%22FAV_USER_3%22%3Bs%3A1%3A%221%22%3Bs%3A10%3A%22FAV_USER_4%22%3Bs%3A1%3A%221%22%3Bs%3A13%3A%22FAV_MODULES_1%22%3Bs%3A1%3A%225%22%3Bs%3A13%3A%22FAV_MODULES_2%22%3Bs%3A1%3A%225%22%3Bs%3A13%3A%22FAV_MODULES_3%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_1%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_2%22%3Bs%3A1%3A%225%22%3Bs%3A10%3A%22FAV_MAIL_3%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_1%22%3Bs%3A1%3A%225%22%3Bs%3A12%3A%22FAV_ARTICLES%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_2%22%3Bs%3A1%3A%225%22%3Bs%3A14%3A%22FAV_ARTICLES_3%22%3Bs%3A1%3A%225%22%3B%7D', '2010-07-16 12:13:29', 1, 'N');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


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
