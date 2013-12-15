<?php

/**
 * Users Component
 *
 * @package Croogo.Users.Controller.Component
 * 
 * 
 */
App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class GroupsUsersComponent extends Component {
	public $UserModel;
	public $user_id;
	public $cityModel;
	
/**
 * initialize
 *
 * @param Controller $controller instance of controller
 */
	
	public function initialize(Controller $controller) {
		
		$this->controller = $controller;
		$this->Auth = $controller->Auth;
		$this->UserModel=ClassRegistry::init('Users.User');
		$this->GroupsUserModel=ClassRegistry::init('Groups.GroupsUser');
		$this->CityModel=ClassRegistry::init('Contacts.City');
		
	}

/**
 * Startup
 *
 * @param object $controller instance of controller
 * @return void
 */
	public function startup(Controller $controller) { $loggedUser=false;
		$user=false;
		$showList=false;
		$user_id=$this->controller->Auth->User('id');
		if (!in_array($this->controller->action,array('logout','login'))){
            			if($user_id<>null)
			{ 
                
				$groups=$this->getUserGroups($user_id);
				$showList=($this->controller->action=='GroupsUserList')?false:(count($groups)>1);
				$this->controller->user_logged=true;
				$loggedUser=$this->controller->Auth->User('name');
				
			}
			else{
				//$user=$this->Auth->user();
			}
		}
		$this->controller->set('loggeduser',$loggedUser );
		$this->controller->set('showList',$showList);
		$this->controller->user_id=($this->controller->Auth->User('id'))?$this->Auth->User('id'):false;
	}
/**
 * beforeRender
 *
 * @param object $controller instance of controller
 * @return void
 */
	public function beforeRender(Controller $controller) {
			}

/**
 * Users
 *
 * Users will be available in this variable in views: $blocks_for_layout
 *
 * @return void
 */
	public function getUserContact() {
		return $this->UserModel->getUserContact($this->user_id);
	}

/**
 * Process blocks for bb-code like strings
 *
 * @param array $blocks
 * @return void
 */
public function setTempUserForGroup(){
	$data=array();
	$data['User']['username']=date("jnyGi").'@gmail.com';
	$data['User']['password']=date("jnyGi");
	//$userd['last_group_id']= $this->Group->getInsertID();
	$data['User']['role_id']= 4;
	$data['User']['verify_password']=$data['User']['password'];
	
	return $data;
}
public function setForAddMember(){
	$contact=$this->getUserContact();
	if (!empty($contact)){
		$this->controller->request->data['GroupsUser']['Member']['Contact']=$contact['User']['Contact'];
		$this->controller->request->data['GroupsUser']['Member']['Child']['Contact']=$contact['User']['Contact'];
		$this->controller->request->data['GroupsUser']['Member']['Child']['Contact']['name']=null;
		$this->controller->request->data['GroupsUser']['Member']['Child']['Contact']['id']=null;
		
		$children=$this->UserModel->Contact->ContactsRelation->getContactChildren($contact['User']['Contact']['id']);
		
	}
		$cities = $this->CityModel->find('list',array('fields'=>array('City.title','City.title')));
		$this->controller->set(compact('cities','contact','children'));
	
}
public function setUserMemberToGroup($data){
	if (isset($data['GroupsUser'][1]['User']['Contact']['Child']))
		$data['GroupsUser'][1]['User']['ContactsRelation']['Contact']=$data['GroupsUser'][1]['User']['Contact']['Child'];
		unset($data['GroupsUser'][1]['User']['Contact']['Child']);
		$data['GroupsUser'][1]['User']['Contact']['ContactsRelation']['relation_type']="child";
		
	return $data;
}
public function _sendEmail($from, $to, $subject, $template, $emailType, $theme = null, $viewVars = null) {
	if (is_null($theme)) {
		$theme = $this->theme;
	}
	$success = false;

	try {
		$email = new CakeEmail();
		$email->from($from[1], $from[0]);
		$email->to('or.reznik@gmail.com');
		$email->subject($subject);
		$email->template($template);
		$email->viewVars($viewVars);
		$email->theme($theme);
		$email->emailFormat('html');
		$success = $email->send();
	} catch (SocketException $e) {
	pr($e->getMessage());
		$this->log(sprintf('Error sending %s notification : %s', $emailType, $e->getMessage()));
	}

	return $success;
}
function getUserGroups($user_id){
	return $this->GroupsUserModel->getGroupsByUser($user_id);
}
}
