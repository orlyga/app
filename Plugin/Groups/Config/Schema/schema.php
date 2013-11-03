<?php

class GroupsSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'role_alias' => array('column' => 'alias', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	CREATE TABLE IF NOT EXISTS `groups` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) CHARACTER SET hebrew COLLATE hebrew_bin NOT NULL,
	`term_id` varchar(50) CHARACTER SET hebrew COLLATE hebrew_bin DEFAULT NULL,
	`term_sub_id` varchar(50) CHARACTER SET hebrew COLLATE hebrew_bin DEFAULT NULL,
	`contact_id` int(11) DEFAULT NULL,
	`user_id` int(11) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`updated` datetime DEFAULT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
	
	-- --------------------------------------------------------
	
	--
	-- Table structure for table `groups_users`
	--
	
	CREATE TABLE IF NOT EXISTS `groups_users` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` int(11) NOT NULL,
			`role_id` int(11) NOT NULL,
			`member_id` int(11) DEFAULT NULL,
			`group_id` int(11) DEFAULT NULL,
			`created` datetime NOT NULL,
			PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	
	
	ALTER TABLE `contacts` ADD `last` VARCHAR( 255 ) NULL AFTER `name` ,
  ADD `city_id` int(11) NOT NULL AFTER `address2`,
  ADD `cellphone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `phone`,
  ADD `image` varchar(255) CHARACTER SET utf8 DEFAULT 'anon.jpg' AFTER `email`,
  ADD `birth_date` date DEFAULT NULL AFTER `image`,
  ADD `gender` tinyint(1) DEFAULT NULL AFTER `birth_date`,
  ADD `contact_method_id` int(11) NOT NULL AFTER `gender`,
 
	ADD INDEX ( `last` )
	
	CREATE TABLE IF NOT EXISTS `members` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) DEFAULT NULL,
	`contact_id` int(11) DEFAULT NULL,
	`grouprole_id` int(11) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`updated` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `fk_members_users1_idx` (`user_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
	
	CREATE TABLE IF NOT EXISTS `contacts_relations` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`contact_id` int(11) NOT NULL,
	`relation_id` int(11) NOT NULL,
	`related_contact_id` int(11) NOT NULL,
	`created` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `contact_id` (`contact_id`,`related_contact_id`),
	KEY `contact_id_2` (`contact_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	
	public $roles_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'role_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'granted_by' => array('type' => 'integer', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pk_role_users' => array('column' => array('user_id', 'role_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'role_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'website' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'activation_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'image' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'bio' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'timezone' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 10, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

}
