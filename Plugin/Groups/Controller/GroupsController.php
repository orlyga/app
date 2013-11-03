<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('GroupsAppController', 'Groups.Controller');

/**
 * Users Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo.Users.Controller
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class GroupsController extends GroupsAppController {

/**
 * Components
 *
 * @var array
 * @access public
 */
	public $name='Groups';
	public $components = array(
		'Users.Users',
			'Groups.GroupsUsers',
		'Search.Prg' => array(
			'presetForm' => array(
				'paramType' => 'querystring',
			),
			'commonProcess' => array(
				'paramType' => 'querystring',
				'filterEmpty' => true,
			),
		),
	);

/**
 * Preset Variables Search
 *
 * @var array
 * @access public
 */
	public $presetVars = true;
	public $user;
	
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $helpers = array('Js','Acl.Acl','Croogo.CroogoForm');
	public $uses = array('Contacts.Contact','Groups.Group','Groups.GroupsUser','Taxonomy.Taxonomy','Groups.Member','Users.User','Contacts.City');
	public $dontAutoLogin=false;
/**
 * implementedEvents
 *
 * @return array
 */	
var $allowedExtensions = array();
	//orly added
	function beforeFilter() {
		
		$this->Auth->allow(array('test','view_nouser'));
		if((in_array($this->action,array('edit',"add",'invite')))&&(!empty($this->request->data))) 
			$this->request->params['requested']=1;
		$this->dontAutoLogin=true;
	parent::beforeFilter();
	
	
		
	//	if(!in_array($this->action,array('view_nouser','add','add_mem_instance','importgroup')))	
		//	$this->redirect('/users/users/login');
	
    }

/**
 * Notify user when failed_login_limit hash been hit
 *
 * @return bool
 */
	function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}
	function view($group_id = null) {
      if (isset($this->request->params['group_id_session']))
      $group_id=$this->_switchGroup($this->request->params['group_id_session']);
      
		if (empty($group_id)){
                if($this->Session->check('Group.Group'))
				        $group_id=$this->Session->read('Group.Group');
			        else
				        $group_id=$this->Auth->user('last_group_id');
			        if (empty($group_id))
				        {
			       // $this->Session->setFlash(__d('croogo', 'Wrong Group information.'), 'default', array('class' => 'error'));
			        $this->redirect('/groupslist');
		        }
        }

		$this->Group->recursive = 0;
		
		$role_id=$this->GroupsUser->getUserRoleInGroup($this->Auth->user('id'),$group_id);
		
        if(!$role_id) $role_id=6;
         $exist=Cache::read('element_mem_list1_'.$role_id.'_'.$group_id,'groups_view');
      //   if($exist) $check="exist"; else $check= 'not exist';
        if(!$exist) {

            $date=date('Y-m-d H:i:s');

            $this->Group->id=$group_id;

           

            $this->Group->saveField('updated',$date,false);

          //  $this->response->cache($date,'999 days');

        }
        else {

            $group=$this->Group->read(null,$group_id);

            $date=$group['Group']['updated'];

        }
         $this->response->modified($date);
          if ($this->response->checkNotModified($this->request)) {

             return $this->response;
        }
        if (!isset($group['Group']['updated']))
             $group=$this->Group->read(null,$group_id);
		$this->Session->write('Group.Type',$group['Group']['term_id']);
		$user_id=$this->Auth->User('id');
		$child_members=$this->GroupsUser->get_members_by_group($group_id,"child-member","Contact.name",$this->contact_id);
		$head_staff=$this->GroupsUser->get_members_by_group($group_id,array("head-staff"),"Contact.name",$this->contact_id);
		$staff_members=$this->GroupsUser->get_members_by_group($group_id,array("staff","head-staff"),"Contact.name",$this->contact_id);
		$group_ad=$this->GroupsUser->getUserGroupByGroupandRole($group_id,5,5,2,'*');
		foreach ($group_ad as $admin){
			$group_admin[]['Contact']=$admin['User']['Contact'];
		}		$contact_id=$this->contact_id;
		$this->set(compact('role_id','child_members','group','head_staff','staff_members','contact_id','user_id','group_admin'));
		$this->render('Groups/view');
	}
	function view_old($group_id = null) {
      
		if (empty($group_id)){
                if($this->Session->check('Group.Group'))
				        $group_id=$this->Session->read('Group.Group');
			        else
				        $group_id=$this->Auth->user('last_group_id');
			        if (empty($group_id))
				        {
			       // $this->Session->setFlash(__d('croogo', 'Wrong Group information.'), 'default', array('class' => 'error'));
			        $this->redirect('/groupslist');
		        }
        }
		
		$this->Group->recursive = 0;
		$group=$this->Group->read(null,$group_id);
		$this->Session->write('Group.Type',$group['Group']['term_id']);
		$role_id=$this->GroupsUser->getUserRoleInGroup($this->Auth->user('id'),$group_id);
		if(!$role_id) $role_id=6;
		$user_id=$this->Auth->User('id');
		$child_members=$this->GroupsUser->get_members_by_group($group_id,"child-member","Contact.name",$this->contact_id);
		$head_staff=$this->GroupsUser->get_members_by_group($group_id,array("head-staff"),"Contact.name",$this->contact_id);
		$staff_members=$this->GroupsUser->get_members_by_group($group_id,array("staff","head-staff"),"Contact.name",$this->contact_id);
		$group_ad=$this->GroupsUser->getUserGroupByGroupandRole($group_id,5,5,2,'*');
		foreach ($group_ad as $admin){
			$group_admin[]['Contact']=$admin['User']['Contact'];
		}		$contact_id=$this->contact_id;
		$this->set(compact('role_id','child_members','group','head_staff','staff_members','contact_id','user_id','group_admin'));
		$this->render('Groups/view');
	
	}
	function view_nouser() {
		$group_id=$this->Session->read('noUser.Group');
		$member_id=$this->Session->read('noUser.Member');
		$this->GroupsUser->Member->recursion=0;
		$contact=$this->GroupsUser->Member->read(array('Contact.name,Contact.last'),$member_id);
		$name=$contact['Contact']['name']." ".$contact['Contact']['last'];
		$this->Group->recursion=-1;
		$group_name=$this->Group->read('name',$group_id);
		$group_name=$group_name['Group']['name'];
		$modal=true;
		$this->Session->write('redirect',"/view/".$group_id*$group_id);
		$this->set(compact('member_id','modal','name','group_name'));
		$this->view($group_id);
	
	}
	private function _switchGroup($group_id=null){
    	//if(!$this->Session->check('Session_key')) $this->redirect('/');
    	if($group_id==null) $this->redirect('/');
    	//$session=$this->Session->read('Session_key');
    	$group_id=sqrt($group_id);
    	//$this->Session->delete('Session_key');
		$this->User->set($this->Auth->User());
		$this->User->saveField('last_group_id',$group_id);
		$this->Session->write('Group.Group',$group_id);
        return $group_id;
				
		}
    function switchGroup($group_id=null){
    	if(!$this->Session->check('Session_key')) $this->redirect('/');
    	if($group_id==null) $this->redirect('/');
    	$session=$this->Session->read('Session_key');
    	$group_id=$group_id/$session;
    	$this->Session->delete('Session_key');
		$this->User->set($this->Auth->User());
		$this->User->saveField('last_group_id',$group_id);
		$this->Session->write('Group.Group',$group_id);
        $this->autorender=FALSE;
        $this->redirect('/groupsview');
        $this->render('view');
				
		}
		
	private function set_member_data($data){
		if (isset($data['GroupsUser']['Member'])){
			if(!isset($data['Member']))
				$data['Member']=$data['GroupsUser']['Member'];
		}
	
		if (!isset($data['User']['username'])&&(!isset($data['User']['id']))){
			$data['User']['id']=$this->Auth->user('id');
			$data['User']['username']=$this->Auth->user('username');
		}
	
		unset($data['GroupsUser']);
	
		return $data;
	}
	//////////////////////////////////////
	
	private function _setData($data){
//creates two entries in user-data, one for temp user and one for the user who created the group - he would be the 
//group admin - these entries will be established without members. if admin is also a member, the member_id would be
//updated in next step.
		$data['Contact']=$data['Group']['Contact'];
		unset($data['Group']['Contact']);
		$data['Contact']['name'] = $data['Group']['name'];
		$groupUser=$this->GroupsUsers->setTempUserForGroup();
		//$data['GroupsUser'][0]['Member']['member_type']='master';
		$data['GroupsUser'][0]['User']=$groupUser['User'];
		$data['GroupsUser'][0]['role_id']=4;
		$data['GroupsUser'][1]['role_id']=5;
		$data['GroupsUser'][1]['user_id']=$user_id=$this->Auth->User('id');
	//	$data['GroupsUser'][1]['Member']['member_type']='master';
		$data['Contact']['email'] = $data['GroupsUser'][0]['User']['username'];
		return $data;
		
	}
	
/*********************ADD GROUP******************************/	
	function add($mode="") {
		$return=true;
	if (!empty($this->request->data)){
		
			$data=$this->request->data;
			$data=$this->_setData($data);
                unset($data['Group']['User']);
				//as initiator of the group, this is the admin
					$return=$this->Group->saveAll($data,array('deep'=>true));
				if ($return) {
					$group=$this->Group->getInsertID();
					$this->Session->write("Group.Group",$group);
					//could be 1. create member for admin
                    if(isset($this->request->data['Group']['redirect'])) 
                        $this->redirect($this->request->data['Group']['redirect']);
				}
		//	}
		
	}	
		
		//if not requested or request failed
	if (empty($this->request->data)||(!$return))
		{
			//$this->layout = "ajax";
            
			if(!$this->user_logged) //unregistered user
				{ 
					$this->Session->renew();
					$this->Session->write('redirect',"/groups/groups/add");
					$this->redirect("/register");
				}
			$terms= $this->Taxonomy->getTree('group-types',array(
					'key' => 'id',
					'value' => 'title',	));
                    
			$this->GroupsUsers->setForAddMember();
			$this->set(compact('terms'));
			$this->render('add');
		}
	
	}
	private function _enderror($class="Group"){
		$this->request->data[$class]['request_status'] = 'error';
		CakeLog::write('debug', 'Group saved failed:');
		$terms= $this->Taxonomy->getTree('group-types',array(
				'key' => 'id',
				'value' => 'title',
		));
		$cities = $this->Group->Contact->City->find('list');
		$this->set(compact('cities'));
		$this->set(compact('terms'));
		$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
	
	}
	
	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'view','group_id'=>$id));
		}
		if (!empty($this->request->data)) {
			$this->Group->create();
			$this->Group->id=$id;
			if ($this->Group->saveAll($this->request->data)) {
                 Cache::delete('element_group_info'.$id);
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'view','group_id'=>$id));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Group->read(null, $id);
		}
		$terms = $this->Group->Term->find('list');
		$termSubs = $this->Group->TermSub->find('list');
		$this->set(compact('terms', 'termSubs'));
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group', true));
			$this->redirect(array('action' => 'view','group_id'=>$id));
		}
		$this->Group->Create();
		$this->Group->id=$id;
		$this->recursive=0;
		if ($this->Group->deleteAll($id)) {
			$this->Session->setFlash(__('Group deleted', true));
			$this->redirect(array('action' => 'view','group_id'=>$id));
		}
		$this->Session->setFlash(__('Group was not deleted', true));
		$this->redirect(array('action' => 'view','group_id'=>$id));
	}
	function admin_index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}
	
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('group', $this->Group->read(null, $id));
	}
	
	function admin_add() {
		if (!empty($this->request->data)) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		$terms = $this->Group->Term->find('list');
		$termSubs = $this->Group->TermSub->find('list');
		$this->set(compact('terms', 'termSubs'));
	}
	
	function admin_edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Group->read(null, $id);
		}
		$terms = $this->Group->Term->find('list');
		$termSubs = $this->Group->TermSub->find('list');
		$this->set(compact('terms', 'termSubs'));
	}
	
	function admin_delete($id = null) {
		//$id=SessionComponent::read('Group.Id');
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id)) {
			$this->Session->setFlash(__('Group deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Group was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function invite(){
		if(!empty($this->request->data)){
			$filename = isset($this->request->data['Import']['import_file']['name']) ? $this->request->data['Import']['import_file']['name'] : "" ;
			if ($filename<>""){
				$data = $this->_importfile($filename);
				if (!$data) return;
			}
			elseif (isset ($this->request->data['message']))
			$data=$this->_setInvitePrint();
			$group_id=$this->Session->read('Group.Dest.Id');
			if(empty($group_id))
				$group_id = $this->Session->read('Group.Id');
			$data['group_id']=	$group_id;
			//$group=$this->GroupsUser->getRegistrationInfo($group_id);
			$user_id=$this->Auth->User('id');
			if(empty($user_id)){
				$gu=$this->GroupsUser->getGroupManager($group_id);
				$contact['Contact']=$gu['Member']['Contact'];
			}
			else {
				$contact=$this->GroupsUser->getContactsByuser($user_id);
			}
			$data['username']=	$group['User']['username'];
			//$data['username']=	SessionComponent::read('User.username');
			$data['email']=$contact[0]['Contact']['email'];
			$data['contact_id']=$contact[0]['Contact']['id'];
			//$import=$this->Import->find('first',array('conditions'=>array('Import.group_id'=>$group_id,'Import.type_id'=>$data['type_id'])));
			//if (isset($import['Import']['id'])) $data['id']=$import['Import']['id'];
	
			//$this->Import->save($data);
			if ($this->redirectto <>"/"){
				$content_for_layout=$this->Printing->print_many($data);
				$this->layout='print';
				$this->set(compact('content_for_layout'));
				$this->render('/prints/printpreview');
			}
			else
				$this->redirect($this->redirectto);
			//"send email with information";
			//group_id,username,location of file;
	
		}
		else {
            $group_id=NULL;
			if($this->Session->check('Group.Group'))
			    $group_id=$this->Session->read('Group.Group');
			if(empty($group_id))
				$group_id = $this->Session->read('Group.Id');
			$user=$this->Group->getTempUser($group_id);
			$this->Group->recursive=-1;
			$group=$this->Group->read("Group.*",$group_id);
			$this->set(compact('user','group'));
			$this->render('invite');
		}
	
	}
	private function _setInvitePrint(){
		$data =  $this->request->data['Import'];
		$data['message']=$this->request->data['message'];
		$data['type_id']='message';
		if (isset($data['amount']))
			$this->redirectto="/prints/printpreview/invitations";
		return $data;
	
	}
	function test($params){

		pr($this->Group->getTempUser($params));
		exit;
	}
	
	}
	
