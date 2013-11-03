<?php

App::uses('ContactsAppModel', 'Contacts.Model');

/**
 * Contact
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo.Contacts.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Contact extends ContactsAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Contact';

/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Croogo.Cached' => array(
			'groups' => array(
				'contacts',
			),
		),
	);

/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $emailcell=-1;
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
				
			'last' => array(
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
				
				
			'phone' => array(
					'rule' => array('phone', null,"il"),
					'required' => false,
					'allowEmpty' => true,
					'message' => 'Please provide a valid phone number',
			),
			'cellphone' => array(
					'rule' => array('cellphone', null,"il"),
							'message' => 'Please provide a valid phone number',
							'last'=>true,
					
					//'atLeastOne' => array(
					//		'rule' => 'atLeastOne',
					//		'message' => 'Please provide with at least one email or one cellular phone number',
					//		'last' => false,
				//	),
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'email' => array(
					'atLeastOne' => array(
							'rule' => 'atLeastOne',
							'message' => 'Please provide with at least one email or one cellular phone number',
							'last' => false,
					),
					'email' => array(
							'rule' => 'email',
							'message' => 'Please provide a valid email address.',
							'last' => false,
							'required' => false,
							'allowEmpty' => true,
					),
						
						
			),
			'city_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => 'This field cannot be left blank.',
						'last' => true,
						),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'image' => array(
			// http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::uploadError
			'uploadError' => array(
				'rule' => 'uploadError',
				'message' => 'Something went wrong with the file upload',
				'required' => FALSE,
				'allowEmpty' => TRUE,
			),
			// http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::mimeType
			//'mimeType' => array(
			//	'rule' => array('mimeType', array('image/gif','image/png','image/jpg','image/jpeg')),
			//	'message' => 'Invalid file, only images allowed',
			//	'required' => FALSE,
			//	'allowEmpty' => TRUE,
			//),
			// custom callback to deal with the file upload
			'processUpload' => array(
				'rule' => 'processUpload',
				'message' => 'Something went wrong processing your file',
				'required' => FALSE,
				'allowEmpty' => TRUE,
				'last' => TRUE,
			)
		),
			'status' => array(
					'boolean' => array(
							'rule' => array('boolean'),
							//'message' => 'Your custom message here',
							//'allowEmpty' => false,
							//'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
			),
	);

/**
 * Model associations: hasMany
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
			'Term' => array(
					'className' => 'Taxonomy.Term',
					'foreignKey' => 'familyrole_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			
			'Term' => array(
					'className' => 'Taxonomy.Term',
					'foreignKey' => 'contact_method_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
	);
	public $hasMany = array(
			'ContactsRelation' => array('className' => 'Contacts.ContactsRelation',
										'foreignKey' => 'contact_id',),
			'ContactsRelation' => array('className' => 'Contacts.ContactsRelation',
					'foreignKey' => 'related_contact_id',),
			'Member' => array('className' => 'Groups.Member',
					'foreignKey' => 'contact_id',),
		'Message' => array(
			'className' => 'Contacts.Message',
			'foreignKey' => 'contact_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '3',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
		),
	);
	

/**
 * Display fields for this model
 *
 * @var array
 */
	protected $_displayFields = array(
		'id',
		'email',
	);
	/**
	 * Upload Directory relative to WWW_ROOT
	 * @param string
	 */
	public $uploadDir = 'uploads';

	/**
	 * Before Validation Callback
	 * @param array $options
	 * @return boolean
	 */
	public function beforeValidate($options = array()) {
		// ignore empty file - causes issues with form validation when file is empty and optional
		if (!empty($this->data[$this->alias]['image']['error']) && $this->data[$this->alias]['image']['error']==4 && $this->data[$this->alias]['image']['size']==0) {
			unset($this->data[$this->alias]['image']);
		}

		return parent::beforeValidate($options);
	}

	/**
	 * Before Save Callback
	 * @param array $options
	 * @return boolean
	 */
 public function afterSave() {
         App::import( 'CakeSession');

        $group=CakeSession::check('Group.Group');
         if( $group){

			$group=CakeSession::read('Group.Group');
            //foreach role of group need to makey it dynamic foreach role in the role table
            Cache::delete('element_mem_list1_4_'.$group,'groups_view');

    		Cache::delete('element_mem_list2_4_'.$group,'groups_view');
             Cache::delete('element_mem_list1_5_'.$group,'groups_view');

    		Cache::delete('element_mem_list2_5_'.$group,'groups_view');
             Cache::delete('element_mem_list1_6_'.$group,'groups_view');

    		Cache::delete('element_mem_list2_6_'.$group,'groups_view');

         }
     }
	public function beforeSave($options = array()) {
		// a file has been uploaded so grab the filepath
		if (!empty($this->data[$this->alias]['filepath'])) {
			$this->data[$this->alias]['image'] = $this->data[$this->alias]['filepath'];
		}
		
	if (!empty($this->data[$this->alias]['image']['error']) && $this->data[$this->alias]['image']['error']==4 && $this->data[$this->alias]['image']['size']==0) {
			unset($this->data[$this->alias]['image']);
		}
		return parent::beforeSave($options);
	}

	/**
	 * Process the Upload
	 * @param array $check
	 * @return boolean
	 */
	public function processUpload($check=array()) {
		// deal with uploaded file
		if (!empty($check['image']['tmp_name'])) {

			// check file is uploaded
			if (!is_uploaded_file($check['image']['tmp_name'])) {
				return FALSE;
			}

			// build full filename
			$image = WWW_ROOT . $this->uploadDir . DS . time().'.'.pathinfo($check['image']['name'], PATHINFO_EXTENSION);

			// @todo check for duplicate filename

			// try moving file
			if (!move_uploaded_file($check['image']['tmp_name'], $image)) {
				return FALSE;

			// file successfully uploaded
			} else {
				// save the file path relative from WWW_ROOT e.g. uploads/example_filename.jpg
				$this->data[$this->alias]['image']=$this->data[$this->alias]['filepath'] = "/".str_replace(DS, "/", str_replace(WWW_ROOT, "", $image) );
			}
		}

		return TRUE;
	}
	function atLeastOne($data){
		if ($this->emailcell==-1){
			$this->emailcell=(empty($data))?0:1;
			return true;
		}
		elseif(($this->emailcell==0)&&(empty($data))) return false;
		return true;
	}
		function addChildContact($contact){
		$data=array('Contact'=>$contact,'ContactsRelation'=>array('relation_type'=>"first-parent",
											'related_contact_id'=>$contact['parent_id']));

		if($this->ContactsRelation->saveAll($data)){
			return $this->getInsertID();
		}
		
		return false;
	}
	//if type is empty, get all relatives
	function getRelatedContact($contact_id,$relation_type=null){
		return $this->ContactsRelation->getContactsOfRelatedByContactId($contact_id,$relation_type);
	}
	//$query should be field name and value sets expects name, last, email and maybe cell
	function isContactExist($query){
		unset($query['city']);
		//this is basically used now for new contacts, not existing!!
		$this->recursive=-1;
		$res=$this->find('first',array(
				'conditions'=>$query,
				'recursive'=>-1)
		);
		//if contact information was changed into some contact that we recognize in the system,
		//we need to merge old contact with new if contact exists.Future version.
		//if its a new contact, then we point it to an existing contact_id
		if(count($res)>0) {
			//$this->redirect(array('action'=>'edit',$res['Contact']['id'],$contact_type));
			$res['match']='full';
			return $res;
		}
		//we take out the name to see if there exist email or cellphone
		$name=$query['name'];
		$last=$query['last'];
		unset($query['name']);
		unset($query['last']);
		if (isset($query['cellphone'])) $cell=' or cellphone="'.$query['cellphone'].'"'; else $cell= "";
		$res=$this->find('first',array(
				'conditions'=>array('email="'.$query['email'].'"'.$cell),
				'recursive'=>-1)
		);
		//
		if(count($res)>0) {
			$lev_number=(strlen($name)<4) ? 1:3;
			$match_last=levenshtein( $last ,$res['Contact']['last'] );
			$match_name=levenshtein( $name ,$res['Contact']['name'] );
			if (($match_name<=$lev_number)&&($match_last<=2)){
			$res['match']='partial';
			return $res;
			}
		}
		return false;
		exit;
		

	}
	function test(){
		echo "hi";
		exit;
	}

}
