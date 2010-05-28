--
-- Generation Time: May 28, 2010 at 01:01 PM

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `cycles`
--

CREATE TABLE IF NOT EXISTS `cycles` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(100) character set utf8 collate utf8_unicode_ci default NULL,
  `autoplay` tinyint(1) unsigned NOT NULL default '1',
  `loop` tinyint(1) unsigned NOT NULL default '1',
  `delay` tinyint(4) unsigned NOT NULL default '5',
  `background_hex` varchar(11) character set utf8 collate utf8_unicode_ci default '000000',
  `created` timestamp NULL default NULL,
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `cycles_nodes`
--

CREATE TABLE IF NOT EXISTS `cycles_nodes` (
  `cycle_id` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `position` tinyint(2) unsigned NOT NULL default '1',
  `style` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default 'jquery_infinite_carousel',
  `width` int(2) unsigned NOT NULL default '500',
  `height` int(2) unsigned NOT NULL default '200',
  KEY `node_id` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cycle_records`
--

CREATE TABLE IF NOT EXISTS `cycle_records` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(100) character set utf8 collate utf8_unicode_ci default NULL,
  `caption` text character set utf8 collate utf8_unicode_ci,
  `link` varchar(200) character set utf8 collate utf8_unicode_ci default NULL,
  `path` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `mime_type` varchar(50) character set utf8 collate utf8_unicode_ci default NULL,
  `created` timestamp NULL default NULL,
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `cycle_records_cycles`
--

CREATE TABLE IF NOT EXISTS `cycle_records_cycles` (
  `cycle_id` int(11) unsigned NOT NULL,
  `cycle_record_id` int(11) unsigned NOT NULL,
  KEY `cycle_id` (`cycle_id`),
  KEY `cycle_record_id` (`cycle_record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

