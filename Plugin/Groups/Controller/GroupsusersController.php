<?php
App::uses('Hash', 'Utility');
//App::import('Controller', 'Prints');
App::uses('CakeEmail', 'Network/Email');
App::uses('GroupsAppController', 'Groups.Controller');

class GroupsusersController extends GroupsAppController {

	var $name = 'Groupsusers';
	var $helpers = array('Js','Croogo.CroogoForm');
	var $uses = array('Groups.Group','Groups.GroupsUser','Contacts.Contact','Contacts.ContactsRelation');
	//var $uses = array('Groups.Group','Members.Member','Contacts.Contact','Users.User','Groups.GroupsUser','Families.Family','Imports.Import','Contacts.ContactsRelation');
	var $final=array();
	var $group=null;
	//var $components=array('Groups.Grouphelp');
	var $components=array('Groups.GroupsUsers');
	
	var $redirectto;
	
	
	public function beforeFilter() {
		if(in_array($this->params['action'],array('add_member_from_tmp_user','importgroup','invite'))){
			$this->Security->csrfCheck=false;
			$this->Auth->allow(array('importgroup','invite'));
			}
            if($this->Session->check('Group.Group'))
			    $this->group=$this->Session->read('Group.Group');
            parent::beforeFilter();
		if(!$this->user_logged){
			
					if ($this->params['action']=='GroupsUserList') {$this->redirect('/home-page');}
					if(!in_array($this->params['action'],array('importgroup','invite')))	$this->redirect('/home-page');
		}
		
	}
	

function GroupsUserList($user_id=null){
	//I think deprecated function??
	cakeLog::write('debug','GroupsUserList in GroupsUser controller is in use');
	if(empty($user_id)) {
		$user_id=$this->Auth->User('id');
		if (empty($user_id)){
			$is_log=$this->Auth->checkUserLogged();
			if($is_log) $user_id=$this->Auth->User('id');
			else return false;
		}
	}
	
	//if(empty($user_id))
	//	$user_id=$this->Auth->User('id');
//	if (empty($user_id)) return false;
	if($this->Session->check('noUser.Group')){
		$group=$this->Session->read('noUser.Group');
		$this->Session->delete('noUser.Group');
		$this->redirect('/groups/view/'.$group);
	}
	$groups=$this->GroupsUser->getGroupsByUser($user_id);
	if (count($groups)==1){
	if(isset($groups[0])) $groups=$groups[0];
		$this->Session->write('Group.Group',$groups['GroupsUser']['group_id']);
		$this->redirect('/groupsview/');
	}
	if(count($groups)==0) {$this->redirect('/home-page');}
	$session=date("Gisu");
	$this->Session->write('Session_key',$session);
	$this->set(compact('groups','session'));
	
}
//is used also when importing groups

function delete_member($member_id){
if (!$member_id) {
			$this->Session->setFlash(__('Invalid id for member', true));
			$this->redirect(array('plugin'=>'groups','controller'=>'groups','action'=>'view'));
		}
		if ($this->GroupsUser->deleteByMemeber($member_id)) {
			$this->Session->setFlash(__('Member deleted', true));
			$this->redirect(array('plugin'=>'groups','controller'=>'groups','action'=>'view'));
		}
		$this->Session->setFlash(__('Member was not deleted', true));
			$this->redirect(array('plugin'=>'groups','controller'=>'groups','action'=>'view'));
}
}
?>