<?php

App::uses('GroupAppModel', 'Groups.Model');

/**
 * Role
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo.Users.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Group extends GroupsAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Group';


/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'name' => array(
				'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => 'This field cannot be left blank.',
						'last' => true,
						),
				'alphaNumericW' => array(
						'rule' => 'alphaNumericW',
						'message' => 'Special signs are not allowed here',
						'last' => true,
						),
				 ),
			

	);
	

/**
 * Display fields for this model
 *
 * @var array
 */
	protected $_displayFields = array(
		'id',
		'name',
	);
	public $belongsTo = array(
			'Term' => array(
					'className' => 'Taxonomy.Term',
					'foreignKey' => 'term_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'TermSub' => array(
					'className' => 'Taxonomy.Term',
					'foreignKey' => 'term_sub_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Contact' => array(
					'className' => 'Contacts.Contact',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
			
	
	);
	
	public $hasMany = array(
			'GroupsUser' => array('className' => 'Groups.GroupsUser'),
	);
	public $hasOne = array(
			
	);
	public function getTempUser($group_id){
		return $this->GroupsUser->getUserByUserRole($group_id,4,1);
	}
	public function getGroupCity($group_id){
		$this->recursive=0;
		$ret=$this->find('first',array('conditions'=>array('Group.id'=>$group_id),'fields'=>array('Contact.city_id')));
		if ($ret) return $ret['Contact']['city_id'];
		return false;
	}
	
}
