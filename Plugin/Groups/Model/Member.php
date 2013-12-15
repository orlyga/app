<?php
App::uses('GroupAppModel', 'Groups.Model');
/**
 * Member Model
 *
 * @property Contact $Contact
 * @property Term $Term
 * @property MessageRelation $MessageRelation
 */
class Member extends GroupsAppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $name = 'Member';
	
	public $belongsTo = array(
		'Contact' => array(
			'className' => 'Contacts.Contact',
			'foreignKey' => 'contact_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
		/*	'Term' => array(
					'className' => 'Taxonomy.Term',
					'foreignKey' => 'member_type',
					'conditions' => 'Term.slug=Member.member_type',
					'fields' => '',
					'order' => ''
			)*/
	);

/**
 * hasMany associations
 *
 * @var array
 */
		public $hasOne = array(
		'GroupsUser' => array('className' => 'Groups.GroupsUser'),
	/*	'MessageRelation' => array(
			'className' => 'Messages.MessageRelation',
			'foreignKey' => 'member_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)*/
	);
	public function getRoleByUser($userid,$groupid) {
		$result=$this->find('first',array(
				'joins' => array(array('table'=> 'groups_users',
						'type' => 'INNER',
						'alias' => 'GroupsUser',
						'conditions' => array('GroupsUser.member_id = Member.id','GroupsUser.group_id ='.$groupid,'GroupsUser.user_id ='.$userid ),
				))
				));
		if (!isset($result['Term']['id'])) {
			return false;
		}
		else{
			$term = array ('id'=>$result['Term']['id'],'slug'=>$result['Term']['slug']);
			return $term;
		}
	}
	public function getMemberIdByUserGroup($user_id,$group_id) {
		$member = $this->find('first',array(
				'joins' => array(array('table'=> 'groups_users',
						'type' => 'INNER',
						'alias' => 'GroupsUser',
						'conditions' => array('GroupsUser.member_id = Member.id','GroupsUser.group_id ='.$group_id,'GroupsUser.user_id ='.$user_id ),
				)),
				));
		if (isset($member['Member']['id'])) {
			return $member['Member']['id'];
		}
		else	return false;
	}

public function getMemberFirstContact($member_id) {
	$member = $this->find('first',array(
			'conditions'=>array(
					'Member.id' =>$member_id)));
	if (isset($member['Contact']['family_id'])) {
		$family_id=$member['Contact']['family_id'];
		$family= $this->Contact->Family->getFirstContact($family_id);
		return $family['Contact'];
	}
	else
	{	
		return $member['Contact'];
	}

}
public function getMemberContacts($member_id,$one_child=false) {
	$member = $this->find('first',array(
			'conditions'=>array(
					'Member.id' =>$member_id)));
	if (isset($member['Contact']['family_id'])) {
		$family_id=$member['Contact']['family_id'];
		if ($one_child) $one_child=$member_id;
		$family= $this->Contact->Family->getContacts($family_id,$one_child);
		return $family;
	}
	else
	{
		$contacts[0]['Contact']=$member['Contact'];	
		return $contacts;
	}

}
public function getMembersByRole($group_id,$role) {
	$this->recursive=0;
	$member = $this->find('all',array(
			'conditions'=>array('GroupsUser.group_id ='.$group_id,'GroupsUser.role_id='.$role)
			));
	return $member;
}

 /*
    expects:
    $data['GroupsUser']['Member']
                    ['Contact']
                            >'id'
                            >rest of contact info - if contact was changed
                    ['member_type']
    ['ContactsRelation'][0]
            ['Parent']
    ['ContactsRelation'][1]
            ['Parent']
    ['Contact']  
    if contact exists allows to change infomration - like if a new photo was uploaded
    saves contact relation info 
    */
public function save_member($data_mem){
    
    	  	//update existing contact information of parent or adult member
    	  	if(!empty($data_mem['Contact']['id'])){
    			$this->Contact->id=$data_mem['Contact']['id'];
                //set parents contact id for finding their users later on
                $parents=$this->Contact->ContactsRelation->getContactsOfRelatedByContactId($data_mem['Contact']['id']);
    			if($parents){
                     $data_mem['ContactsRelation'][0]['related_contact_id']  = $parents[0]['Parent']['id'];
    			        if ( $parents[1])
                         $data_mem['ContactsRelation'][1]['related_contact_id']  = $parents[1]['Parent']['id'];

                }
               if(!empty($data_mem['Contact']['name'])){
                        if(!$this->Contact->save($data_mem['Contact'])) {
    				        $err=$this->Contact->getError();
    				        //$this->Session->setFlash(__($err));
    				        return false;
    			        }
    	  	        }
            }
            //contact's parent are new in the system
    	    if (isset($data_mem['ContactsRelation'][0]['parent']['name'])){
                $data_mem['ContactsRelation'][0]['Contact']=$data_mem['Contact'];
                 if (isset($data_mem['ContactsRelation'][1]))
                    $data_mem['ContactsRelation'][1]['Contact']=$data_mem['Contact'];
               
                 $data_mem['ContactsRelation']=  $this->Contact->ContactsRelation->setContactRelations($data_mem['ContactsRelation']);
                               if (! $data_mem['ContactsRelation']) return FALSE;
                  $data_mem['GroupsUser']['Member']['contact_id']=$data_mem['ContactsRelation'][0]['Contact']['id'];
            }
            $contact_id=isset($data_mem['ContactsRelation'][0]['Contact']['id'])? $data_mem['ContactsRelation'][0]['Contact']['id']:$data_mem['Contact']['id'];
           //if record for this contact (this is the member's contact) then create one, else no need to add
            $gu=$this->GroupsUser->isContactInGroup($data_mem['GroupsUser']['group_id'],$contact_id);
       if(!$gu) {$data_mem['GroupsUser']['Member']['contact_id']=$contact_id;
                $gu=$this->save($data_mem['GroupsUser']['Member']);
               $data_mem['GroupsUser']['member_id']=$this->getInsertId();
              unset($data_mem['GroupsUser']['Member']);
              return $data_mem;
       }
         //   echo "hi";
         
         else{
            
            $data_mem['GroupsUser']['member_id'] = $gu['GroupsUser']['member_id'];
            return $data_mem;
           // exit;
           // return $this->getInsertId();
            //create users ang GU for new member
           // $this->User->
            }
}
}
