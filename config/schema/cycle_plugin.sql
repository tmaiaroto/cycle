-- phpMyAdmin SQL Dump
-- version 3.0.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2010 at 07:01 PM
-- Server version: 5.0.77
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `cycles`
--

CREATE TABLE IF NOT EXISTS `cycles` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) default NULL,
  `created` timestamp NULL default NULL,
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cycle_records_cycles`
--

CREATE TABLE IF NOT EXISTS `cycle_records_cycles` (
  `cycle_id` int(11) NOT NULL,
  `cycle_record_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cycles_nodes`
--

CREATE TABLE IF NOT EXISTS `cycles_nodes` (
  `cycle_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cycle_records`
--

CREATE TABLE IF NOT EXISTS `cycle_records` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) default NULL,
  `caption` text,
  `link` varchar(200) default NULL,
  `path` varchar(255) default NULL,
  `mime_type` varchar(50) default NULL,
  `created` timestamp NULL default NULL,
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
