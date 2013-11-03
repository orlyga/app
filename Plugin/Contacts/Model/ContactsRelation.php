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
class ContactsRelation extends ContactsAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'ContactsRelation';

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
	
	
	public $belongsTo = array(
			'Contact' => array('className' => 'Contacts.Contact','foreignKey' => 'contact_id',),
			'Parent' => array('className' => 'Contacts.Contact','foreignKey' => 'related_contact_id',),
					//'Term' => array('className' => 'Taxonomy.Term','foreignKey' => 'relation_id',),
			
	);
	
/**
 * Display fields for this model
 *
 * @var array
 */
	
	
	public function getContactChildren($contact_id,$one_child=false) {
		$this->recursive=-1;
		$result=$this->find('all',array(
				'conditions'=>array('ContactsRelation.related_contact_id='.$contact_id,),
				'joins'=>array(array(
						'table'=>'contacts',
						'alias'=>'Contact',
						'type' => 'LEFT',
						
						'conditions'=>array('ContactsRelation.contact_id=Contact.id'))),
				'fields'=>array('Contact.*'),
				));
		return $result;
	
	}
	public function findContactChild($contact_id,$child) {
		
		$this->recursive=-1;
		if(!empty($child['id'])) $condition='Contact.id='.$child['id'];
		else $condition='Contact.name="'.$child['name'].'"';
		$result=$this->find('all',array(
				'joins'=>array(array(
						'table'=>'contacts',
						'alias'=>'Contact',
						'type' => 'LEFT',
	
						'conditions'=>array('ContactsRelation.contact_id=Contact.id',$condition))),
				'fields'=>array('Contact.*'),
				'conditions'=>array('ContactsRelation.related_contact_id='.$contact_id,),
		));
		return $result;
	
	}
	function getContactsOfRelatedByContactId($contact_id,$relation_type=null){
		if ($relation_type==null) $type_arr=array();
		else $type_arr=array('ContactsRelation.relation_type'=>$relation_type);
		$results=$this->find('all',array(
				'fields'=>array('Parent.*','ContactsRelation.relation_type'),
				'conditions'=>array(
								'ContactsRelation.contact_id'=>$contact_id,$type_arr),
				'recursive'=>0)
				);
		$i=0;		
		foreach ($results as $result) {
			$results[$i]['Parent']['relation_type']=$results[$i]['ContactsRelation']['relation_type'];
			unset($results[$i]['ContactsRelation']);
			$i++;
		}
		if( $i==1) return $results[0];
		return $results;
	}
	function getContacts($contact_id,$mem_only=false){
		if (!$mem_only){
			$this->recursive=1;
			$result=$this->find('all',array(
					'conditions'=>array(
							'ContactsRelation.related_contact_id'=>$contact_id,
					)
			));
		}
		else {
			$res1[0]=$this->GetChildContactByMember($mem_only);
	
			$result=$this->Contact->find('all',array(
					'conditions'=>array(
							'Contact.id'=>$mem_only,
					)
			));
				
			$result = array_merge($res1,$result);
				
		}
		return $result;
	}
	function GetChildContactByMember($mem){
		$result=$this->Contact->find('first',array(
				'recursive'=>1,
				'conditions'=>array('Term.slug'=>'child'),
				'joins' => array(
						array('table' => 'members',
								'alias' => 'Members',
								'type' => 'INNER',
								'conditions' => array('Members.contact_id = Contact.id','Members.id'=>$mem,)
						),
				),
		)
		);
		return $result;
	}
	//expects ['ContactsRelation']['Parent']
	//['ContactsRelation']['contact_id']
	//['ContactsRelation']['relation_type'] to be set
	function checkContactsExists($data){
	$res=$this->Contact->isContactExist($data['ContactsRelation']['Parent']);
		//parent was found, need to check its children
		if ($res) {
		if(isset($data['ContactsRelation']['contact_id']))
			$data['ContactsRelation']['Contact']['id']=$data['ContactsRelation']['contact_id'];
		$contact=$this->_isChildExist($res['Contact']['id'],$data['ContactsRelation']['Contact']);
			
			//found the specific child
			if($contact) $data['found_child']=$contact;
			//only parent was found
			else
			{
				unset($data['ContactsRelation']['Parent']);
				$data['ContactsRelation']['related_contact_id']=$res['Contact']['id'];
			}
		}
		return $data;
	}
	//expects ['ContactsRelation']['Parent']
	//['ContactsRelation']['contact_id']
	//['ContactsRelation']['relation_type'] to be set
	function setParent($data){
		$data=$this->checkContactsExists($data);
		if(isset($data['found_child'])) return true;
		return $this->saveAll($data,array('deep'=>true));
	}
	function addChildContact($contact){
		$data=array('Contact'=>$contact,'ContactsRelation'=>array('relation_type'=>"first-parent",
				'related_contact_id'=>$contact['parent_id']));
	
		if($this->saveAll($data,array('deep'=>true))){
			return $this->getInsertID();
		}
		else return $data;
	}
	function setNewContantandChild($data,$check_exist=false){
	if(!isset($data['ContactsRelation'])){
		$tosave['ContactsRelation']['Parent']=$data['GroupsUser']['Member']['Contact'];
		$tosave['ContactsRelation']['Contact']=$data['GroupsUser']['Member']['Child'];
	}
	else $tosave=$data;
	if($check_exist){
		$tosave=$this->checkContactsExists($tosave);
		//if child was found, returns the contact_id of the child.
		if(isset($tosave['found_child'])) return $tosave['found_child'];
	}
	$tosave['ContactsRelation']['relation_type']='first-parent';
	if($this->saveAll($tosave,array('deep'=>true))){
		$contact=$this->Contact->getInsertId();
		return $contact;
		}
	else {
		//if array was returns then there was a problem
		//structure expected to be 
		//['ContactsRelation']['Parent']
		//['ContactsRelation']['Contact']
		return $tosave;
	}
		
		
	}
	private function _isChildExist($contact_id,$child){
	//we check if child related to this parent
		$res=$this->findContactChild($contact_id,$child);
		if ($res) return $res[0]['Contact']['id'];
		$res=$this->getContactChildren($contact_id,$child);
		$name=$child['name'];
		if (count($res)>0){
			foreach ($res as $onechild){
				$lev_number=(strlen($name)<4) ? 1:3;
				$match_name=levenshtein( $name ,$onechild['Contact']['name'] );
				if (($match_name<=$lev_number)){
					return $onechild['Contact']['id'];
				}
			}
		}
		return false;
		
	}
	function getUserbyContactChild($contact_id){
		$this->recursive=-1;
		$result=$this->find('first',array(
				'conditions'=>array('ContactsRelation.contact_id='.$contact_id),
				'joins'=>array(array(
						'table'=>'users',
						'alias'=>'Users',
						'type' => 'INNER',
	
						'conditions'=>array('Users.contact_id= ContactsRelation.related_contact_id'))),
				'fields'=>array('Users.id'),
		));
		return $result;
	}
	function test(){
		echo "hi";
		exit;
	}
	

}