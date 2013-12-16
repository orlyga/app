<?php

/**
 * Failed login attempts
 *
 * Default is 5 failed login attempts in every 5 minutes
 */

Croogo::hookRoutes('Groups');
CroogoCache::config('croogo_groups', array_merge(
	Configure::read('Cache.defaultConfig'),
	array('groups' => array('groups'))
));

CroogoNav::add('groups', array(
	'icon' => array('group', 'large'),
	'title' => __d('croogo', 'Groups'),
	'url' => array(
		'admin' => true,
		'plugin' => 'groups',
		'controller' => 'groups',
		'action' => 'index',
	),
	'weight' => 50,
	'children' => array(
		'groups' => array(
			'title' => __d('croogo', 'Groups'),
			'url' => array(
				'admin' => true,
				'plugin' => 'groups',
				'controller' => 'groups',
				'action' => 'index',
			),
			'weight' => 10,
		),
	),
));
Croogo::mergeConfig('Wysiwyg.actions', array(
'Groups/add' => array(
array(
'elements' => 'Stam',
'preset' => 'basic',

),

),
'Groups/invite' => array(
array(
'elements' => 'ImportMessage',
'preset' => 'basic',

),
),
));