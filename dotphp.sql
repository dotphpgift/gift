-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 30, 2012 at 05:53 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dotphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `dphp_config`
--

CREATE TABLE IF NOT EXISTS `dphp_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `dphp_config`
--

INSERT INTO `dphp_config` (`id`, `updated`, `key`, `value`) VALUES
(1, '2012-06-23 04:25:52', 'name', 's:17:"DOTPHP Developers";'),
(2, '2012-06-23 04:25:52', 'basePath', 's:24:"E:\\xampp\\htdocs\\gift\\app";'),
(3, '2012-06-23 04:25:52', 'theme', 's:7:"default";'),
(5, '2012-06-23 04:25:52', 'params', 'a:0:{}'),
(6, '2012-06-24 05:43:56', 'language', 's:2:"en";'),
(7, '2012-06-23 05:52:34', 'components', 'a:2:{s:2:"db";a:4:{s:16:"connectionString";s:44:"mysql:host=localhost;port=3306;dbname=dotphp";s:8:"username";s:6:"dhanam";s:8:"password";s:8:"04121976";s:11:"tablePrefix";s:5:"dphp_";}s:7:"session";a:2:{s:5:"class";s:24:"application.lib.DSession";s:9:"autoStart";b:1;}}'),
(8, '2012-06-24 20:07:29', 'license', 's:4:"demo";'),
(9, '2012-07-02 18:48:40', 'defaultController', 's:9:"home/site";');

-- --------------------------------------------------------

--
-- Table structure for table `dphp_menu`
--

CREATE TABLE IF NOT EXISTS `dphp_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`menu_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dphp_menuitems`
--

CREATE TABLE IF NOT EXISTS `dphp_menuitems` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `description` text NOT NULL,
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `fk_menu_item_menu1` (`menu_id`),
  KEY `fk_menu_item_menu_item1` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dphp_user`
--

CREATE TABLE IF NOT EXISTS `dphp_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(120) NOT NULL,
  `activationKey` varchar(128) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `lastvisit` int(10) NOT NULL DEFAULT '0',
  `lastaction` int(10) NOT NULL DEFAULT '0',
  `lastpasswordchange` int(10) NOT NULL DEFAULT '0',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `notifyType` enum('None','Digest','Instant','Threshold') DEFAULT 'Instant',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dphp_menuitems`
--
ALTER TABLE `dphp_menuitems`
  ADD CONSTRAINT `fk_menuitems_menu1` FOREIGN KEY (`menu_id`) REFERENCES `dphp_menu` (`menu_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_menuitems_menu_item1` FOREIGN KEY (`parent_id`) REFERENCES `dphp_menuitems` (`item_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
