<?php
App::uses('Hash', 'Utility');
App::uses('GroupsAppController', 'Groups.Controller');
App::uses('CakeEmail', 'Network/Email');

class MembersController extends GroupsAppController {
	var $name = 'Members';
	var $helpers = array('Js','Croogo.CroogoForm','Groups.Group');
	var $group=null;
	//var $helpers = array('Js','Upload');
	var $uses=array('Groups.GroupsUser','Groups.Member','Users.User','Contacts.Contact','Contacts.ContactsRelation');
	var $components=array('Groups.GroupsUsers','Email','Twilio.Twilio');
	
	public function beforeFilter() {

			if((in_array($this->action,array('resend_member_invite_email','edit_child_member','edit_adult_member',"add","add_member_from_tmp_user",'add_member_admin','add_member_by_group_admin')))&&(!empty($this->request->data)))
			$this->request->params['requested']=1;
			$this->Auth->allow(array('resend_member_invite_email','member_group_approval','resend_member_invite_bulck','activate_member','add_member_from_tmp_user'));
			if(!$this->Auth->checkUserLogged())
			if(!in_array($this->params['action'],array('add_member_from_tmp_user','activate_member')))	$this->redirect('/logout');
		if ($this->Session->check('Group.Group')) $this->group=$this->Session->read('Group.Group');		
		parent::beforeFilter();
			
}

	//differ registration activites due to authrization rules

	
function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid member', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('member', $this->Member->read(null, $id));
	}
	
	
	function viewByRole($role) {
		$this->set('member', $this->Member->read(null, $id));
	}
	
	
	function activate_member($activation_key){
		$this->Member->recursive=0;
		$ret=$this->Member->find('first',array('conditions'=>array('activation_key'=>$activation_key)));
		if(!$ret) {
			$this->Session->setFlash(__d('croogo', 'You cannot use this link to connect to the group.'), 'default', array('class' => 'error'));
			$this->redirect('/');
		}
		

		//if(empty($ret['GroupsUser']['user_id'])){
			$this->Session->write('noUser.Group',$ret['GroupsUser']['group_id']);
			$this->Session->write('noUser.Member',$ret['Member']['id']);
			
			$this->redirect('/viewgroup');
		//}
		exit;
	}
    function member_group_approval(){
	$member=$this->Session->read('noUser.Member');
	$member_type=$this->GroupsUser->activateMember($member,$this->Auth->User('id'));
	if($member_type){
		$this->Session->setFlash(__('Your activation was successful, Welcome to your group/', true), 'default', array('class' => 'success'));
		
		$this->Session->delete('noUser.Member');
			$this->redirect('/groupsview#'.$member_type);
	}
	else {
		$this->Session->setFlash(__('Member was not approved, Please contact you group administrator.', true));
		$this->redirect('/viewgroup');
		
		
	}
    }
    //routes from addMember
    function add_member_by_group_admin($member_type='child-member'){
    	$default_city=$this->GroupsUser->Group->getGroupCity($this->group);
    	 
    	if (!empty($this->request->data)){
    			//first we save the contact child/parent or contact adult
    		//if its a child
    		$this->request->data['GroupsUser']['Member']['status']=0;
    		$act_key=md5(uniqid());
    		$this->request->data['GroupsUser']['Member']['activation_key']=$act_key;
    		$this->request->data['GroupsUser']['role_id']=6;
			$redirect="/";
			if(isset($this->request->data['redirect'])) {
				$redirect=$this->request->data['redirect'];
				unset($this->request->data['redirect']);
				if($member_type=='staff'){
					$redirect=$redirect."#staff";
				}
			}
			$email=($member_type<>'child-member')? 	$this->request->data['GroupsUser']['Member']['Contact']['email']: $this->request->data['ContactsRelation']['Parent']['email'];
    		if($this->save_member()){
    			if(!empty($email)){
    				$template=($member_type=='child-member')?"Groups.invite_member":"Groups.invite_staff";
	    			$group_name=$this->GroupsUser->Group->read('name',$this->group);
	    			$group_name=$group_name['Group']['name'];
	    			$contact=array('group_name'=>$group_name,'activation_key'=>$act_key,'name'=>$this->request->data['GroupsUser']['Member']['Contact']['name']);
					$subject ="הצטרפו ל:" .$group_name;
					//$newsubject=mb_encode_mimeheader($subject, 'UTF-8', 'B', "\r\n"); 			
	    			$this->request->data['Contact']=$contact;
					$this->Email->to      = $email;
					$this->Email->subject = htmlspecialchars($group_name);
					$this->Email->from    = Configure::read('Site.email');
					$this->Email->sendAs  = 'html'; 
					$this->Email->template=$template;
					//$ret=$this->Email->send('Gmail');
					$ret=true;
	    			if ($ret){
	    					$this->Session->setFlash(__('Email was sent to new member', true), 'default', array('class' => 'info'));
	    				}
	    				
	    			}
	    		else 	$this->Session->setFlash(__('Member staff was added successfuly', true), 'default', array('class' => 'success'));
	    			
	    			$this->redirect($redirect);
	    			
	    		
    		}
    			else {
    				$this->set("error",'yes');
    			}
    	}
    	else {
    		$this->request->data['ContactsRelation']['Contact']['city']=$default_city;
    		
    	}
    	$this->request->data['GroupsUser']['Member']['member_type']=$member_type;
    	
    	$this->set('group_id',$this->group);
    	$group_type=4;
    	if($this->Session->check('Group.Type'))
    		$group_type=$this->Session->read('Group.Type');
    	$this->set(compact('default_city','member_type','group_type'));
		$this->autorender=false;
    	//$cities = $this->Contact->City->find('list',array('fields'=>array('City.title','City.title')));
    	if($member_type=='child-member')
			$this->render("Members/add_child_member");
    	else 
    		$this->render("Members/add_member");
    }
    function add_member_admin(){
    	
    	if (!empty($this->request->data)){
    		
    		//find the record that was added previously
    		$groupsuser=$this->GroupsUser->getUserGroupByGroupandRole($this->group,5,1);
    		$this->request->data['GroupsUser']['id']=$groupsuser['GroupsUser']['id'];
    		//$this->request->data['redirect']='/invitetogroup';
    		$return=$this->save_member();
    	}
    	   	$this->generic_add_member();
    	
    }
    private function save_member(){
	    	$return=true;
    	$redirect_anchor="";
    	  	$data=$this->request->data;
    	  	//update existing contact information of parent or adult member
    	  	if(!empty($data['GroupsUser']['Member']['Contact']['id'])){
    			$this->Contact->id=$data['GroupsUser']['Member']['Contact']['id'];
    			if(!$this->Contact->save($this->request->data['GroupsUser']['Member']['Contact'])) {
    				$err=$this->Contact->getError();
    				$this->Session->setFlash(__($err));
    				return false;
    			}
    	  	}
    	  
    	  if(in_array($data['GroupsUser']['Member']['member_type'],array('child-member','member'))){
    	  	////////////////new Child/////////////////////
    	  	if (!empty($data['ContactsRelation']['Contact']['name'])){
    	  	//new parent and new child
	    	  	if(empty($data['ContactsRelation']['Parent']['id']))
	    	  			$newContact=$this->Contact->ContactsRelation->setNewContantandChild($this->request->data,true);
	    	  	else	{
		    	  		$this->request->data['ContactsRelation']['Contact']['parent_id']=$data['ContactsRelation']['Parent']['id'];
		    	  		//unset($data['GroupsUser']['Member']['Contact']);
		    	  		//A new child contact was added
		    	  		$newContact=$this->Contact->addChildContact($this->request->data['ContactsRelation']['Contact']);
	    	  		 
	    	  			}
    	  		if (is_array($newContact)){
    	  			$return=false;
    	  		}
    			else {
    				$data['GroupsUser']['Member']['contact_id']=$newContact;
    				unset($data['GroupsUser']['Member']['Child']);
    			}
    		}
    		////////////////existing Child for existing parent/////////////////////
    		elseif (!empty($data['ContactsRelation']['Contact']['id'])){
    			$data['GroupsUser']['Member']['contact_id']=$data['GroupsUser']['Member']['Child']['Contact']['id'];
    			unset($data['GroupsUser']['Member']['Child']);
    		}
    		else {
    			echo "error: no member selected";
    			$return=false;
    		}
    	}
    	//////////////////for staff//////////////////////////
    	else {
    		////new contact
    		unset($data['GroupsUser']['Member']['Child']);
    		$redirect_anchor="#staff";
    		if(empty($data['GroupsUser']['Member']['Contact']['id'])){
    			if(!$this->Contact->save($data['GroupsUser']['Member']['Contact'])) {
    				$err=$this->Contact->getError();
    				$this->Session->setFlash(__($err));
     				return false;
    			}
    			else 
    				$data['GroupsUser']['Member']['contact_id']=$this->Contact->getinsertId();
    		}
    		else  ////existing contact)
    		$data['GroupsUser']['Member']['contact_id']=$data['GroupsUser']['Member']['Contact']['id'];
    	}
     if ($return){
       	//we want to update the user infornmation in case it was changed. and not open a new contact id for the member,
       	//so eventually the user and member are pointing to the same contact
       	unset($data['GroupsUser']['Member']['Contact']);
    		$data['GroupsUser']['Group']['id']=$this->group;
    		if (isset($data['GroupsUser']['id']))
    			$this->GroupsUser->id=$data['GroupsUser']['id'];
    		$return=$this->GroupsUser->saveAll($data,array('deep'=>true));
    		
    		if ($return) {
    			return $return;
    
    		}
    		else {
    		
    			$err=$this->GroupsUser->getError();
    			$this->Session->setFlash(__($err));
    			return false;
    		}
    	}
    }
    function add_member_from_tmp_user($tempuser){
    	if (!empty($this->request->data)){
    		$this->request->data['GroupsUser']['role_id']=6;
    		$this->request->data['redirect']='/groupsview';
    		$ret=$this->save_member();
    		if (!$ret) $this->set("error",'yes');
    	}
    
    	$group=$this->GroupsUser->getGroupsByUsername($tempuser."@gmail.com");
    	if (!$group) {
    		$this->Session->setFlash(__('Invalid Group, Please check the number at the end of the web address string'), 'default', array('class' => 'error'));
    		$this->response = $this->render('../Errors/error');
    
    		$this->response->send();
    		$this->_stop();
    	}
    	$this->Session->write("Group.Group",$group['GroupsUser']['group_id']);
    
    	if(!$this->user_logged) //unregistered user
    	{
    		$this->Session->renew();
    		$this->Session->write('redirect',"/add/".$tempuser);
    		$this->redirect("/register");
    		return;
    	}
    
    	$this->generic_add_member();
    }
    private function generic_add_member(){
    	$this->GroupsUsers->setForAddMember();
    	$default_city=$this->Member->GroupsUser->Group->getGroupCity($this->group);
    	$this->set(compact('default_city'));
    	$this->set('group_id',$this->group);
    	$this->render("Members/add_member");
    
    }
    function edit_child_member($id = null) {
    	$this->Session->write('Redirect',"/".$this->params->url);
    	if (!$id && empty($this->request->data)) {
    		$this->Session->setFlash(__('Invalid member', true));
    		$this->redirect(array('pulgin'=>'groups','controller'=>'groups','action' => 'view'));
    	}
    	if (!empty($this->request->data)) {
    		$this->Member->create();
    		$this->Member->id=$id;
    		$group=$this->Session->read('Group.Group');
    		if ($this->Member->saveAll($this->request->data)) {

    			Cache::delete('element_mem_list1_'.$group,'groups_view');
    			Cache::delete('element_mem_list2_'.$group,'groups_view');
    			$this->Session->setFlash(__('The member has been saved', true), 'default', array('class' => 'success'));
    			if(isset($this->params['redirect']))
    				$this->redirect(array('pulgin'=>'groups','controller'=>'groups','action' => 'view'));
    
    			$this->request->data['Member']['request_status'] = 'success';
    			if ($this->RequestHandler->isAjax()){
    				$this->layout = "ajax";
    				$cities = $this->Member->Contact->City->find('list');
    				$this->set(compact('cities'));
    				$this->render($render);
    			}
    
    		} else {
    			$this->request->data['Member']['request_status'] = 'error';
    			$this->Session->setFlash(__('The member could not be saved. Please, try again.', true));
    		}
    	}
    	//if (empty($this->request->data)) {
    	$this->request->data = $this->Member->read(null, $id);
    	$parents=$this->Contact->getRelatedContact($this->request->data['Member']['contact_id']);
    	$this->set(compact('parents'));
    	//echo var_dump($this->data['Family']['Contact']);
    	// }
    
    }
    function edit_adult_member($id=null){
    	$group_type=$this->Session->read('Group.Type');
    	$this->Session->write('Redirect',"/".$this->params->url);
    	if (!$id && empty($this->request->data)) {
    		$this->Session->setFlash(__('Invalid member', true));
    		$this->redirect(array('pulgin'=>'groups','controller'=>'groups','action' => 'view'));
    	}
    	if (!empty($this->request->data)) {
    		$this->Member->create();
    		$this->Member->id=$id;
    		$group=$this->Session->read('Group.Group');
    		if ($this->Member->saveAll($this->request->data)) {
    			Cache::delete('element_mem_list3_'.$group,'groups_view');
    			Cache::delete('element_group_headstaff'.$group,'groups_view');
    			$this->Session->setFlash(__('The member has been saved', true), 'default', array('class' => 'success'));
    				$this->redirect('/groupsview');
    
    			$this->request->data['Member']['request_status'] = 'success';
    			if ($this->RequestHandler->isAjax()){
    				$this->layout = "ajax";
    				$cities = $this->Member->Contact->City->find('list');
    				$this->set(compact('cities'));
    				$this->render($render);
    			}
    
    		} else {
    			$this->request->data['Member']['request_status'] = 'error';
    			$this->Session->setFlash(__('The member could not be saved. Please, try again.', true));
    		}
    	}
    	else {
    	//if (empty($this->request->data)) {
    	$this->request->data = $this->Member->read(null, $id);
    	//echo var_dump($this->data['Family']['Contact']);
    	// }
    	}
    	$member_type=$this->request->data['Member']['member_type'];
    	$this->set(compact('group_type','member_type'));
    	$this->render('Groups.add_member');
    }
    function resend_member_invite_bulck($group_id=null){
    	$members=$this->GroupsUser->get_members_id_by_status($group_id);
    	foreach ($members as $member){
    		//we dont want to bug we send two reminders
    		if($member['Member']['status']<-2) continue;
    		pr(date("Y-m-d"));
    		pr(date("Y-m-d",strtotime($member['Member']['created'])));
    		$days=floor((strtotime(date("Y-m-d"))-strtotime(date("Y-m-d",strtotime($member['Member']['created']))))/(60*60*24));
    		echo $days;
    		exit;
    		if ($member['Member']['status']==0 && $days < 7) continue;
    		if ($member['Member']['status']==-1 && $days < 24) continue
    		$ret=$this->resend_member_invite_email($member['Member']['id'],'invite');
    		if($ret>0)
    		{
    			$this->Member->saveField('status',$member['Member']['status']-1);
    			 
    		}
    	}
    }
    function resend_member_invite_email($member_id,$format){
    	$this->Member->recursive=2;
    	$member=$this->Member->find('first',array('conditions'=>array('Member.id'=>$member_id)));
    	$group_name=$member['GroupsUser']['Group']['name'];
    	$activation_k=$member['Member']['activation_key'];
    	$name=$member['Contact']['name'];
    	$this->request->data['Contact']=array('prefix_text'=>"",'group_name'=>$group_name,'activation_key'=>$activation_k,'name'=>$name);
    	$parents=$this->Contact->ContactsRelation->getContactsOfRelatedByContactId($member['Contact']['id']);
    	$email="";
    	if(!empty($parents['Parent']['email'])){
    		$email=$parents['Parent']['email'];
    	}
    	if(!empty($parents[0]['Parent']['email'])){
    		$email=$parents[0]['Parent']['email'];
    	}
    	if(!empty($parents[1]['Parent']['email']))	{
    		if($email<>"") $email=array($email,$parents[1]['Parent']['email']); else $email=$parents[1]['Parent']['email'];
    	}
    
    	$this->Email->to      = $email;
    	$this->Email->subject = htmlspecialchars($group_name);
    	$this->Email->from    = $this->Auth->User('username');
    	$this->Email->sendAs  = 'html';
    	$this->Email->template="Groups.invite_member";
    	$ret=$this->Email->send();
    	if ($ret) 	echo __('Email was sent to new member');
    	else echo __('Failed to send email. Please try a different email account');
    	if ($this->RequestHandler->isAjax()){
    		$this->layout = "ajax";
    		exit;
    
    	}
    
    }
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for member', true));
			$this->redirect(array('plugin'=>'groups','controller'=>'groups','action'=>'view'));
		}
		if ($this->GroupsUser->deleteByMemeber($id)) {
			$this->Session->setFlash(__('Member deleted', true), 'default', array('class' => 'success'));
			$this->redirect(array('plugin'=>'groups','controller'=>'groups','action'=>'view'));
		}
		$this->Session->setFlash(__('Member was not deleted', true));
		$this->redirect(array('plugin'=>'groups','controller'=>'groups','action'=>'view'));
	}
	function admin_index() {
		$this->Member->recursive = 0;
		$this->set('members', $this->paginate());
	}

	

	
}
