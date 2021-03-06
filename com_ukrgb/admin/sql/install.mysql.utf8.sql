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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ukrgb_riverguide`;
CREATE TABLE `#__ukrgb_riverguide` (
  `id` int(11) NOT NULL,
  `catid` int(10) unsigned NOT NULL,
  `summary` varchar(200) DEFAULT NULL,
  `grade` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
