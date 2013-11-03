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
}
