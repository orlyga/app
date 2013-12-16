<?php

App::uses('UsersAppModel', 'Users.Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * User
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
class User extends UsersAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'User';

/**
 * Order
 *
 * @var string
 * @access public
 */
	public $order = 'User.name ASC';

/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Acl' => array(
			'className' => 'Croogo.CroogoAcl',
			'type' => 'requester',
		),
		'Search.Searchable',
	);

/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
	public $belongsTo = array('Users.Role',
			
			'Contact' => array(
					'className' => 'Contacts.Contact',
					'foreignKey' => 'contact_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
					));

/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'username' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'The username has already been taken.',
				'last' => true,
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
			'username' => array(
				'rule' => 'username',
				'message' => 'User name must be a valid cellular number or email',
				'last' => true,
			),
		),
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Please provide a valid email address.',
				'last' => true,
			),
				
			//'isUnique' => array(
			//	'rule' => 'isUnique',
			//	'message' => 'Email address already in use.',
			//	'last' => true,
		//	),
		),
		'password' => array(
			'rule' => array('minLength', 6),
			'message' => 'Passwords must be at least 6 characters long.',
		),
		'verify_password' => array(
			'rule' => 'validIdentical',
		),
		/*'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
			'validName' => array(
				'rule' => 'validName',
				'message' => 'This field must be alphanumeric',
				'last' => true,
			),
		),*/
		'website' => array(
			'url' => array(
				'rule' => 'url',
				'message' => 'This field must be a valid URL',
				'allowEmpty' => true,
			),
		),
	);

/**
 * Filter search fields
 *
 * @var array
 * @access public
 */
	public $filterArgs = array(
		'chooser' => array('type' => null),
		'name' => array('type' => 'like', 'field' => array('User.name', 'User.username')),
		'role_id' => array('type' => 'value'),
	);

/**
 * Display fields for this model
 *
 * @var array
 */
	protected $_displayFields = array(
		'id',
		'Role.title' => 'Role',
		'username',
		'name',
		'status' => array('type' => 'boolean'),
		'email',
	);

/**
 * Edit fields for this model
 *
 * @var array
 */
	protected $_editFields = array(
		'role_id',
		'username',
		'name',
		'email',
		'website',
		'status',
	);

/**
 * beforeDelete
 *
 * @param boolean $cascade
 * @return boolean
 */
	public function beforeDelete($cascade = true) {
		$this->Role->Behaviors->attach('Croogo.Aliasable');
		$adminRoleId = $this->Role->byAlias('admin');

		$current = AuthComponent::user();
		if (!empty($current['id']) && $current['id'] == $this->id) {
			return false;
		}
		if ($this->field('role_id') == $adminRoleId) {
			$count = $this->find('count', array(
				'conditions' => array(
					'User.id <>' => $this->id,
					'User.role_id' => $adminRoleId,
					'User.status' => true,
				)
			));
			return ($count > 0);
		}
		return true;
	}

/**
 * beforeSave
 *
 * @param array $options
 * @return boolean
 */
	public function beforeSave($options = array()) {
		
        if((empty ($this->data['User']['username']))&& (!empty($this->data['User']['contact_id']))){
            $user=$this->findByContactId($this->data['User']['contact_id']);
            if ($user) return $user;
            $contact=$this->Contact->read(null,$this->data['User']['contact_id']);
            $this->data['User']['username']=(isset($contact['Contact']['email']))?$contact['Contact']['email']:$contact['Contact']['cellphone'];
             if(empty($this->data['User']['username'])) $this->data['User']['username']=$this->Contact->getParentEmail($this->data['User']['contact_id']);
            $this->data['User']['name'] = $contact['Contact']['name'] ." ".$contact['Contact']['last'];
            $this->data['User']['password']=substr($contact['Contact']['email'],6);
            $this->data['User']['role_id']=2; 
            $this->data['User']['email']=(isset($contact['Contact']['email']))?$contact['Contact']['email']:null;
            $this->data['User']['status']=1;
          
        }
		if (!empty($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
        return true;
	}

/**
 * _identical
 *
 * @param string $check
 * @return boolean
 * @deprecated Protected validation methods are no longer supported
 */
	protected function _identical($check) {
		return $this->validIdentical($check);
	}

/**
 * validIdentical
 *
 * @param string $check
 * @return boolean
 */
	public function validIdentical($check) {
		if (isset($this->data['User']['password'])) {
			if ($this->data['User']['password'] != $check['verify_password']) {
				return __d('croogo', 'Passwords do not match. Please, try again.');
			}
		}
		return true;
	}
	public function find_user($options){
		$conditions=array();
		$email="";
			if (isset($options['fb_user'])) {
			if(isset($options['fb_user']['registration']['email'])) $email=$options['fb_user']['registration']['email'];
			elseif (isset($options['fb_user']['email'])) $email=$options['fb_user']['email'];
			$cond_str="";
			if($email<>"") $cond_str='User.username = "'.$email.'" OR ';
			//$cond_str=$cond_str.'User.facebook_id ='.$options['fb_user']['user_id'];
			$conditions=array
			(
					$cond_str.'User.facebook_id ="'.$options['fb_user']['user_id'].'"',
			);
			unset($options['fb_user']);
		}
		if (isset($options['fb_id'])) {
			$conditions=array(
					'User.facebook_id' => $options['fb_id']);
			unset($options['fb_id']);
		}
		$conditions=array_merge($options,$conditions);
		$x= $this->find('first',array(
				'conditions'=>$conditions,
				'recursive' => -1
		));
		//pr($conditions);
		return $x;
	}
	public function findByFacebookId($fb_id){
		return $this->find_user(array('fb_id'=>$fb_id));
	}
	public function find_user_w_error($options){
		$user=$this->find_user($options);
		if((isset($user['User']['status']))&&($user['User']['status']==0)){
			$return=array('error'=>true,'message'=>__('Please confirm you account.'));
			return $return;
		}
		//check if user exists, and its a password problem
		if (!$user){
			unset($options['User.password']);
			$user=$this->find_user($options);
				//found a user with username but with different password
				if ($user) $return=array('error'=>true,'message'=>__('Incorrect Password'));
				//username was not found
				else $return=array('error'=>true,'message'=>__('User name doesn`t exist'));
		return $return;
		}
		else return $user;
		
	}
	public function getUserContact($user_id){
		
		if(!$user_id) return false;
		$result = $this->find('first',array(
				'joins' => array(array('table'=> 'contacts',
						'type' => 'INNER',
						'alias' => 'Contacts',
						'conditions' => array('Contacts.id =User.contact_id','User.id ='.$user_id ),
						
				)),
				'fields'=>'User.id,Contacts.*',
				'recursive'=>0,
		));
		if (isset($result)){
			
			 $result['User']['Contact']=$result['Contacts'];
			 unset($result['Contacts']);
		}

	return $result;
	}
	function test(){
		echo "hi";
		exit;
	}
	public function afterUserwasAddedToGroup($group_id) {
		if(empty($group_id)) return false;
		$this->saveField('last_group_id',$group_id);
		return true;
	}
}
