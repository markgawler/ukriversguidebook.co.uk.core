DROP TABLE IF EXISTS `#__ukrgb_doantion`;
CREATE TABLE `#__ukrgb_doantion` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tx` varchar (20),
  `user_id`	INT(11),
  `phpBB_user_id` INT(11),
  `topic_id` mediumint(8),
  `post_id` mediumint(8) ,
  `mc_gross` DECIMAL(5,2),
  `protection_eligibility` varchar(40),
  `payer_id` varchar(20) ,
  `tax` DECIMAL(5,2),
  `payment_date` varchar(28),  
  `payment_status` varchar (20), 
  `first_name` varchar(40),
  `receipt_reference_number` varchar (20),
  `mc_fee` DECIMAL(5,2),
  `custom`  varchar (20),
  `payer_status` varchar (20),
  `business` varchar(40),
  `quantity`  mediumint(8),
  `payer_email` varchar(40),
  `txn_id` varchar(30),
  `payment_type` varchar (20),
  `last_name` varchar(40),
  `receiver_email` varchar(40),
  `store_id` varchar(20),
  `payment_fee` DECIMAL(5,2),
  `receiver_id` varchar(20),
  `pos_transaction_type` varchar(20),
  `txn_type` varchar(20),
  `item_name` varchar(30),
  `num_offers` varchar(20),
  `mc_currency` varchar(4),
  `item_number` mediumint(8),
  `residence_country` varchar(4),
  `handling_amount`  DECIMAL(5,2),
  `transaction_subject` varchar(40),
  `payment_gross` DECIMAL(5,2),
  `shipping` DECIMAL(5,2),

  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ukrgb_riverguide`;
CREATE TABLE `#__ukrgb_riverguide` (
  `id` int(11) NOT NULL,
  `catid` int(10) unsigned NOT NULL,
  `summary` varchar(200) DEFAULT NULL,
  `grade` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

  
CREATE TABLE IF NOT EXISTS `#__ukrgb_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT ,
  `catid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '' ,
  `calid` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL ,
  `summary` text NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '' ,
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `all_day` boolean DEFAULT 1, /* tru is the event is an all day event*/
  `duration` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '' ,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0' ,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `xreference` varchar(50) NOT NULL ,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL DEFAULT '1',
  `forumid` int(11) NOT NULL DEFAULT '0',
  `threadid` int(11) NOT NULL DEFAULT '0',
  `postid` int(11) NOT NULL DEFAULT '0',

  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  

  
CREATE TABLE IF NOT EXISTS `#__ukrgb_calendar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT ,
  `catid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '' ,
  `forumid` int(10) NOT NULL DEFAULT 0 ,
  `description` text NOT NULL ,
  `post_template` text NOT NULL ,
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '' ,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0' ,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `xreference` varchar(50) NOT NULL ,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  
CREATE TABLE IF NOT EXISTS `#__ukrgb_discipline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT ,
  `catid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL DEFAULT '',
  `alias` varchar(30) NOT NULL DEFAULT '' ,
  `description` text NOT NULL ,
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '' ,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0' ,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `xreference` varchar(50) NOT NULL ,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__ukrgb_skill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT ,
  `catid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL DEFAULT '',
  `alias` varchar(30) NOT NULL DEFAULT '' ,
  `description` text NOT NULL ,
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '' ,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0' ,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `xreference` varchar(50) NOT NULL ,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



