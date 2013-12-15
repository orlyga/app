<?php
App::uses('Hash', 'Utility');
App::uses('ContactsAppController', 'Contacts.Controller');
class ContactsrelationsController extends AppController {
	var $name = 'Contactsrelations';
	var $helpers = array('Js');
//	var $uses=array('ContactsRelation','Contact','Term','Member','GroupsUser');
	var $uses=array('Contacts.Contact','Contacts.ContactsRelation');
	
	public function beforeFilter() {
		if((in_array($this->action,array('add')))&&(!empty($this->request->data)))
			$this->request->params['requested']=1;
		parent::beforeFilter();
		
	}
	
	function add($contact_id,$relation_type='second-parent'){
	
		if (in_array($relation_type,array('first-parent','second-parent')))
			$contact_type='parent';
		$this->set(compact('contact_type'));
		if (!$contact_id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid contact', true),'default', array('class' => 'error'));
			$this->redirect($this->referer());
		}
		if (empty($this->request->data)) {
			$this->Contact->recursive=-1;
			$contact=$this->Contact->read(null,$contact_id);
			
			$this->request->data=$contact;
			$this->request->data['Contact']['name']="";
			$this->request->data['Contact']['email']="";
            $this->request->data['Contact']['cellphone']="";
			$this->request->data['Contact']['id']="";
			$this->request->data['ContactsRelation']['relation_type']=$relation_type;
			$this->request->data['ContactsRelation']['contact_id']=$contact_id;
			$this->request->data['ContactsRelation']['redirect']=$this->referer();
			$this->set('referer',$this->referer());
					}	
		else {
			$this->request->data['Parent']=$this->request->data['Contact'];
			unset($this->request->data['Contact']);
			if($this->ContactsRelation->setParent($this->request->data)){
			$this->Session->setFlash(__('The parent was added', true),'default', array('class' => 'success'));
			
			if (isset($this->request->data['ContactsRelation']['redirect']))
				$this->redirect($this->request->data['ContactsRelation']['redirect']);
			else
			$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The parent could not be saved. Please, try again.', true),'default', array('class' => 'error'));
			}
	}
$this->render('add');	
}
private function _endError(){
	$this->request->data['ContactsRelation']['request_status'] = 'error';
	$cities = $this->Contact->City->find('list');
	$image[0]=$this->request->data['ContactsRelation'][0]['Contact']['image'];
	$image[1]=$this->request->data['ContactsRelation'][1]['Contact']['image'];
	$this->set(compact('cities','image'));
	$this->render('add');
}
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for family', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Family->delete($id)) {
			$this->Session->setFlash(__('Family deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Family was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->Family->recursive = 0;
		$this->set('families', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid family', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('family', $this->Family->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Family->create();
			if ($this->Family->save($this->data)) {
				$this->Session->setFlash(__('The family has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The family could not be saved. Please, try again.', true));
			}
		}
		$groups = $this->Family->Group->find('list');
		$terms = $this->Family->Term->find('list');
		$this->set(compact('groups', 'terms'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid family', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Family->save($this->data)) {
				$this->Session->setFlash(__('The family has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The family could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Family->read(null, $id);
		}
		$groups = $this->Family->Group->find('list');
		$terms = $this->Family->Term->find('list');
		$this->set(compact('groups', 'terms'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for family', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Family->delete($id)) {
			$this->Session->setFlash(__('Family deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Family was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	function index() {
		$this->Family->recursive = 0;
		$this->set('families', $this->paginate());
	}
	
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid family', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('family', $this->Family->read(null, $id));
	}
}
