<?php
class RegistrationsController extends AppController {

var $name = 'Registrations';
var $title_for_layout;
var $image_for_title;
var $helpers=array('Tinymce.Tinymce');
var $uses=array('GroupsUser','Member','Group');
var $components = array('User');
var $mode="";
public function beforeFilter() {
	//case when registration login is done, we will do a hidden login to this temprorary user
	if (isset($this->params['tempuser'] )){
		$this->mode='add_member';
		$data['User']['username']=$this->params['tempuser']."@gmail.com";
		$data['User']['password']=$this->params['tempuser'];
		$this->request->data = $data;
		$this->Auth->logout();
		if(!$this->Auth->login()) {
			//modal - user password are not correct
			$this->redirect("/");
		}
		$this->User->after_login();
		SessionComponent::write('Group.Dest.Id',$this->Auth->User('last_group_id'));
		$this->request->data=null;
		
	}
	//for registered users who want to be added to a given group
	if (isset($this->params['username'] )){
		$this->mode='add_member';
		$data['User']['username']=$this->params['username']."@gmail.com";
		$data['User']['password']=$this->params['username'];
		$this->request->data = $data;
		SessionComponent::write('LoginStatus',"add_logged_member");
		$this->mode='add_logged_member';
		$this->request->data=null;
	
	}
	
	parent::beforeFilter();
	$this->Session->delete('form.data');
	
}
/*valid values for $mode add_logged_member*/
 function index($mode="") {
 	if ($mode<>"")	$this->mode=$mode;
 	$this->set('user_exist',false);
 	if($this->Auth->user('id')){
 	$this->set('user_exist',true);
 	$group_id=	SessionComponent::read('Group.Id');
 	//if(!$group_id) time out;
 	$this->Group->recursive=-1;
 	$group_name=$this->Group->read(array('name'),$group_id);
 	$group_name=$group_name['Group']['name'];
  	}
 	
 	$this->set('mode',$this->mode);
 	$this->set(compact('group_name'));
       }

function add() {
	$this->title_for_layout=__("Create New Group",true);
	$this->image_for_title = "/img/registration.png";
}
}
?>