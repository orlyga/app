<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('ContactsAppController', 'Contacts.Controller');

/**
 * Contacts Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo.Contacts.Controller
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class ContactsController extends ContactsAppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Contacts';

/**
 * Components
 *
 * @var array
 * @access public
 */
	public $components = array(
		'Croogo.Akismet',
		'Croogo.Recaptcha',
	);

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('Contacts.Contact', 'Contacts.Message','Contacts.City');

/**
 * Admin index
 *
 * @return void
 * @access public
 */
	function beforeFilter(){
		//$this->Security->csrfCheck=false;
		$this->Auth->allow(array('setCities','checkContactExist'));
        if((in_array($this->action,array('edit',"add",'checkContactExist')))&&(!empty($this->request->data)))
			$this->request->params['requested']=1;
	parent::beforeFilter();
}
	public function admin_index() {
		$this->set('title_for_layout', __d('croogo', 'Contacts'));

		$this->Contact->recursive = 0;
		$this->paginate['Contact']['order'] = 'Contact.title ASC';
		$this->set('contacts', $this->paginate());
		$this->set('displayFields', $this->Contact->displayFields());
	}

/**
 * Admin add
 *
 * @return void
 * @access public
 */
	//options are json based, find contact according to all parameters recieved.
	//if paramters are also name and last, then check according to algorithm for find close names
	public function checkContactExist($contact_type=null){
			$res=$this->Contact->isContactExist($this->request->query);
		if (!$res) {
			echo "no-match";
			exit; 
		}
		if ($res['match']=='full'){
			$this->redirect(array('action'=>'edit',$res['Contact']['id'],$contact_type));
		}
		else{
            echo '{"status":"partial-match","family":"'.$res['Contact']['last'].'"}';
			exit;
		}
		
		
	}
	public function admin_add() {
		$this->set('title_for_layout', __d('croogo', 'Add Contact'));

		if (!empty($this->request->data)) {
			$this->Contact->create();
			if ($this->Contact->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Contact has been saved'), 'default', array('class' => 'success'));
				$this->Croogo->redirect(array('action' => 'edit', $this->Contact->id));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Contact could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
	}

/**
 * Admin edit
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_edit($id = null) {
		$this->set('title_for_layout', __d('croogo', 'Edit Contact'));

		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__d('croogo', 'Invalid Contact'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->Contact->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The Contact has been saved'), 'default', array('class' => 'success'));
				$this->Croogo->redirect(array('action' => 'edit', $this->Contact->id));
			} else {
				$this->Session->setFlash(__d('croogo', 'The Contact could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Contact->read(null, $id);
		}
	}

/**
 * Admin delete
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__d('croogo', 'Invalid id for Contact'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Contact->delete($id)) {
			$this->Session->setFlash(__d('croogo', 'Contact deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
	}

/**
 * View
 *
 * @param string $alias
 * @return void
 * @access public
 */
	public function view($alias = null) {
		if (!$alias) {
			$this->redirect('/');
		}

		$contact = $this->Contact->find('first', array(
			'conditions' => array(
				'Contact.alias' => $alias,
				'Contact.status' => 1,
			),
			'cache' => array(
				'name' => $alias,
				'config' => 'contacts_view',
			),
		));
		if (!isset($contact['Contact']['id'])) {
			$this->redirect('/');
		}
		$this->set('contact', $contact);

		$continue = true;
		if (!$contact['Contact']['message_status']) {
			$continue = false;
		}
		if (!empty($this->request->data) && $continue === true) {
			$this->request->data['Message']['contact_id'] = $contact['Contact']['id'];
			$this->request->data['Message']['title'] = htmlspecialchars($this->request->data['Message']['title']);
			$this->request->data['Message']['name'] = htmlspecialchars($this->request->data['Message']['name']);
			$this->request->data['Message']['body'] = htmlspecialchars($this->request->data['Message']['body']);
			$continue = $this->_validation($continue, $contact);
			$continue = $this->_spam_protection($continue, $contact);
			$continue = $this->_captcha($continue, $contact);
			$continue = $this->_send_email($continue, $contact);

			if ($continue === true) {
				//$this->Session->setFlash(__d('croogo', 'Your message has been received.'));
				//unset($this->request->data['Message']);

				echo $this->flash(__d('croogo', 'Your message has been received...'), '/');
			}
		}

		$this->set('title_for_layout', $contact['Contact']['title']);
		$this->set(compact('continue'));
	}

/**
 * Validation
 *
 * @param boolean $continue
 * @param array $contact
 * @return boolean
 * @access protected
 */
	protected function _validation($continue, $contact) {
		if ($this->Contact->Message->set($this->request->data) &&
			$this->Contact->Message->validates() &&
			$continue === true) {
			if ($contact['Contact']['message_archive'] &&
				!$this->Contact->Message->save($this->request->data['Message'])) {
				$continue = false;
			}
		} else {
			$continue = false;
		}

		return $continue;
	}

/**
 * Spam protection
 *
 * @param boolean $continue
 * @param array $contact
 * @return boolean
 * @access protected
 */
	protected function _spam_protection($continue, $contact) {
		if (!empty($this->request->data) &&
			$contact['Contact']['message_spam_protection'] &&
			$continue === true) {
			$this->Akismet->setCommentAuthor($this->request->data['Message']['name']);
			$this->Akismet->setCommentAuthorEmail($this->request->data['Message']['email']);
			$this->Akismet->setCommentContent($this->request->data['Message']['body']);
			if ($this->Akismet->isCommentSpam()) {
				$continue = false;
				$this->Session->setFlash(__d('croogo', 'Sorry, the message appears to be spam.'), 'default', array('class' => 'error'));
			}
		}

		return $continue;
	}

/**
 * Captcha
 *
 * @param boolean $continue
 * @param array $contact
 * @return boolean
 * @access protected
 */
	protected function _captcha($continue, $contact) {
		if (!empty($this->request->data) &&
			$contact['Contact']['message_captcha'] &&
			$continue === true &&
			!$this->Recaptcha->valid($this->request)) {
			$continue = false;
			$this->Session->setFlash(__d('croogo', 'Invalid captcha entry'), 'default', array('class' => 'error'));
		}

		return $continue;
	}

/**
 * Send Email
 *
 * @param boolean $continue
 * @param array $contact
 * @return boolean
 * @access protected
 */
	protected function _send_email($continue, $contact) {
		$email = new CakeEmail();
		if (!$contact['Contact']['message_notify'] || $continue !== true) {
			return $continue;
		}

		$siteTitle = Configure::read('Site.title');
		try {
			$email->from($this->request->data['Message']['email'])
				->to($contact['Contact']['email'])
				->subject(__d('croogo', '[%s] %s', $siteTitle, $contact['Contact']['title']))
				->template('Contacts.contact')
				->viewVars(array(
					'contact' => $contact,
					'message' => $this->request->data,
				));
			if ($this->theme) {
				$email->theme($this->theme);
			}

			if (!$email->send()) {
				$continue = false;
			}
		} catch (SocketException $e) {
			$this->log(sprintf('Error sending contact notification: %s', $e->getMessage()));
			$continue = false;
		}

		return $continue;
	}
	//orly added
		public function add($contact_type='adult') {
			if (!empty($this->request->data)) {
			$this->Contact->create();
				$this->request->data['Contact']['image'] = WWW_ROOT.DS.uniqid().'.jpg';
				$this->request->data['Contact']['country']=Configure::read('Session.country');
				//$this->request->data['Contact']['city']= $this->request->data['Contact']['address2'];
				$this->Contact->set($this->request->data);
				if ($this->Contact->save($this->request->data,array(validate=>true,))) {
					$this->request->data['Contact']['request_status'] = 'success';
					$this->request->data['Contact']['id'] = $this->Contact->getInsertID();
		
					$this->set('contact', $this->request->data);
					if ($this->RequestHandler->isAjax()){
						$this->layout = "ajax";
						$this->render('add');
					}
					else{
						$this->request->data['Contact']['request_status'] = 'error';
						$this->Session->setFlash(__('The contact has been saved', true));
						$this->redirect(array('action' => 'index'));
					}
				} else {
					$this->Session->setFlash(__('The contact could not be saved. Please, try again.', true));
				}
					
			}
			else
			{
				$cities = $this->Contact->City->find('list');
				$this->set(compact('cities'));
				$this->layout = "ajax";
				$this->render('add');
			}
}
//supports redirect using the session 'redirect'
public function edit($id = null,$contact_type=null) {
		$this->Contact->id = $id;
        $redirect=NULL;
        if($this->Session->check('Redirect'))
            $redirect=$this->Session->read('Redirect');
        
        $this->set(compact('contact_type'));
		if (!$this->Contact->exists()) {
			throw new NotFoundException(__('Invalid contact'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			if ($this->Contact->save($this->request->data)) {
				$this->Session->setFlash(__('The contact has been saved'));
                if(isset($this->request->data['Contact']['redirect']))
				$this->redirect($this->request->data['Contact']['redirect']);
                else $this->redirect('/');
			} else {
				$this->Session->setFlash(__('The contact could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Contact->read(null, $id);
			
            if($redirect<>NULL)
            $this->request->data['Contact']['redirect']=$redirect;
            if ($this->RequestHandler->isAjax()){
            	$this->layout = "ajax";
            	$this->render('edit');
            }
            
		}
		
		
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Contact->id = $id;
		if (!$this->Contact->exists()) {
			throw new NotFoundException(__('Invalid contact'));
		}
		if ($this->Contact->delete()) {
			$this->Session->setFlash(__('Contact deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Contact was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	public function setCities(){
		$cities = $this->City->find('list',array('fields'=>array('City.title','City.title')));
		$this->set(compact('cities'));
		return $cities;
	}

}
