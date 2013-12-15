<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('UsersAppController', 'Users.Controller','Groups.GroupsUser');

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
class UsersController extends UsersAppController {

/**
 * Components
 *
 * @var array
 * @access public
 */
	public $components = array(
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
	public $fb_user;
	public $fb_registration;
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('Users.User','Groups.GroupsUser');
/**
 * implementedEvents
 *
 * @return array
 */	
var $allowedExtensions = array();

	//orly added
	function beforeFilter() {
		//if $controller->request->data
		//localhostxx
	//	$this->fb_user = $this->Connect->user();
	//	if($this->fb_registration = $this->Connect->registrationData()) {unset($this->request->data);}
		$this->Auth->allow(array('ajax_login','after_register','add','activate','login','facebook_register','facebook_login'));
		if(in_array($this->action,array("ajax_login","register","add",'facebook_register','facebook_login','login'))&&(!empty($this->request->data))) {
			$this->request->params['requested']=1;
			$this->Security->csrfCheck=false;
		}
		parent::beforefilter();
	}

	public function implementedEvents() {
		return parent::implementedEvents() + array(
			'Controller.Users.beforeAdminLogin' => 'onBeforeAdminLogin',
			'Controller.Users.adminLoginFailure' => 'onAdminLoginFailure',
		);
	}

/**
 * Notify user when failed_login_limit hash been hit
 *
 * @return bool
 */
	public function onBeforeAdminLogin() {
		$field = $this->Auth->authenticate['all']['fields']['username'];
		if (empty($this->request->data)) {
			return true;
		}
		$cacheName = 'auth_failed_' . $this->request->data['User'][$field];
		$cacheValue = Cache::read($cacheName, 'users_login');
		if (Cache::read($cacheName, 'users_login') >= Configure::read('User.failed_login_limit')) {
			$this->Session->setFlash(__( 'You have reached maximum limit for failed login attempts. Please try again after a few minutes.'), 'default', array('class' => 'error'));
			return $this->redirect(array('action' => $this->request->params['action']));
		}
		return true;
	}

/**
 * Record the number of times a user has failed authentication in cache
 *
 * @return bool
 * @access public
 */
	public function onAdminLoginFailure() {
		$field = $this->Auth->authenticate['all']['fields']['username'];
		if (empty($this->request->data)) {
			return true;
		}
		$cacheName = 'auth_failed_' . $this->request->data['User'][$field];
		$cacheValue = Cache::read($cacheName, 'users_login');
		Cache::write($cacheName, (int)$cacheValue + 1, 'users_login');
		return true;
	}

/**
 * Admin index
 *
 * @return void
 * @access public
 * $searchField : Identify fields for search
 */
	public function admin_index() {
		$this->set('title_for_layout', __('Users'));
		$this->Prg->commonProcess();
		$searchFields = array('role_id', 'name');

		$this->User->recursive = 0;
		$this->paginate['conditions'] = $this->User->parseCriteria($this->request->query);

		$this->set('users', $this->paginate());
		$this->set('roles', $this->User->Role->find('list'));
		$this->set('displayFields', $this->User->displayFields());
		$this->set('searchFields', $searchFields);

		if (isset($this->request->query['chooser'])) {
			$this->layout = 'admin_popup';
		}
	}

/**
 * Admin add
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->request->data)) {
			$this->User->create();
			$this->request->data['User']['activation_key'] = md5(uniqid());
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__( 'The User has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__( 'The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
				unset($this->request->data['User']['password']);
			}
		} else {
			$this->request->data['User']['role_id'] = 2; // default Role: Registered
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

/**
 * Admin edit
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__( 'The User has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__( 'The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
		$this->set('editFields', $this->User->editFields());
	}

/**
 * Admin reset password
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_reset_password($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__( 'Invalid User'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__( 'Password has been reset.'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__( 'Password could not be reset. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->User->read(null, $id);
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
			$this->Session->setFlash(__( 'Invalid id for User'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__( 'User deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__( 'User cannot be deleted'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
	}


	/**
	 * Login
	 *
	 * @return boolean
	 * @access public
	 */
	 public function ajax_login(){
	 if(isset($this->request->query['value']) && !empty($this->request->query['value'])){
			$user=$this->User->find('first',array(
					'conditions'=>array($this->request->query['field']=>$this->request->query['value']),
					'recursive'=>-1));
		
			if(!empty($user)){

					$this->request->params['requested']=1;
					$this->request->data=$user;
					$this->request->data['User']['username']=array('User.id'=>$user['User']['id']);
					$this->request->data['User']['password']='temp';
					$this->login();
					exit;
			}
	}
	$this->layout='ajax';
	exit;
	
}
	public function login() {
		$this->set('title_for_layout', __( 'Log in'));
$ps= (isset($this->request->params['requested'])&&($this->request->params['requested']==1));
		if ($this->request->is('post')||($ps)) {
			Croogo::dispatchEvent('Controller.Users.beforeLogin', $this);
			if ($this->Auth->login()) {
			if($this->Session->check('noUser.Member')){
					$this->GroupsUser->activateMember($this->Session->read('noUser.Member'),$this->Auth->user('id'));
					$this->Session->delete('noUser.Member');
		}
				Croogo::dispatchEvent('Controller.Users.loginSuccessful', $this);
				if ($this->request->is('ajax')){
					$this->layout = "ajax";
					//$this->_redirectafterlogin();
					echo '{"status":"success","user_name":"'.$this->Auth->User('name').'"}';
					exit;
				}
				else{
					$this->_redirectafterlogin();
					$this->redirect($this->Auth->loginRedirect);
					
				}
			} else {
			
				//send message that acount was not yet activated - if thats the reason
				if ((isset($this->request->data['User']['username']))&&(isset($this->request->data['User']['username']))){
					$user=$this->User->find_user_w_error(array('User.username'=>$this->request->data['User']['username'],
																'User.password'=>$this->Auth->password($this->request->data['User']['password'])));
					if (isset($user['error'])){
						if ($this->request->is('ajax')){
							$this->layout = "ajax";
							echo $user['message'];
							exit;
						}
						$this->Session->setFlash($user['message']);
						$this->redirect($this->Auth->loginAction);
					}
					
				}
				Croogo::dispatchEvent('Controller.Users.loginFailure', $this);
				if ($this->request->is('ajax')){
					$this->layout = "ajax";
					echo 'Login Failed';
					exit;
				}
				$this->Session->setFlash($this->Auth->authError, 'default', array('class' => 'error'), 'auth');
				$this->redirect($this->Auth->loginAction);
			}
		}
	}
	

/**
 * Add
 *
 * @return void
 * @access public
 */
 
 public function facebook_login(){
 	if($this->Auth->checkUserLogged()) return true;
	if(empty($this->fb_user)) $this->fb_user= $this->Connect->user();

	if($this->fb_user){
		$this->autoRender = false;
		$user=$this->User->findByFacebookId($this->fb_user['id']);
		//standard login with facebook
		if(!empty($user)){
				
					$this->request->params['requested']=1;
					$this->request->data=$user;
					$this->request->data['User']['username']=array('User.id'=>$user['User']['id']);
					$this->request->data['User']['password']='temp';
					$this->Session->setFlash(__( 'You are currently logged in as: '.$user['User']['name']), 'default', array('class' => 'success'));
					$this->login();
					return TRUE;
		}
		//facebook is found, but login was not done by facebook
		else {
			$this->Session->setFlash(__('Please register first'), 'default', array('class' => 'error'));
			
				$fb_user=$this->fb_user;
				$this->set(compact('fb_user'));
				$this->render('/Users/facebook_register');

		}
	}
	//facebook is not found, ask for the user to first login to facebook
	else {
			$this->Session->setFlash(__('There is no facebook account available in this machine '), 'default', array('class' => 'error'));

	}
}
	public function facebook_login1(){
	if(empty($this->fb_user)) $this->fb_user= $this->Connect->user();
	if($this->fb_user){
			$this->autoRender = false;
			$user=$this->User->findByFacebookId($this->fb_user['id']);
			if(empty($user)) {
				$user=$this->User->findByusername($this->fb_user['email']);
				
				if (!empty($user)) 	{
				$this->User->id=$user['User']['id'];
					$this->User->saveField('facebook_id', $this->fb_user['id']);
					$this->User->saveField('password', 'temp');
					}
				//we need to establish a new account
				else {
				$fb_user=$this->fb_user;
				$this->set(compact('fb_user'));
				$this->render('/Users/facebook_register');
				}
			}
			//trying to register an existing user
			if(!empty($user)){
				{
					$this->request->params['requested']=1;
					$this->request->data=$user;
					$this->request->data['User']['username']=array('User.id'=>$user['User']['id']);
					$this->request->data['User']['password']='temp';
					$this->Session->setFlash(__( 'You are currently logged in as: '.$user['User']['name']), 'default', array('class' => 'success'));
					$this->login();
					exit;
					
					/*$user1=$this->Auth->login();
					if ($user1) {
						Croogo::dispatchEvent('Controller.Users.loginSuccessful', $this);
						$this->redirect($this->Auth->redirect());
					} else {
						Croogo::dispatchEvent('Controller.Users.loginFailure', $this);
						$this->Session->setFlash($this->Auth->authError, array('class' => 'error'), 'auth');
						$this->redirect($this->Auth->loginAction);
					}*/
	
				}
			}
		}
		//user doesnt exist as facebook user
		else {
			$this->Session->setFlash(__('There is no facebook account available in this machine '), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'add'));
		}
	}
	public function facebook_register(){
	$this->fb_registration = $this->Connect->registrationData();
	if(empty($this->fb_registration)){
		$this->fb_registration = $this->Connect->user();
		$this->fb_registration['user_id']=$this->Connect->get_userid();
		$this->fb_registration['registration']=$this->fb_registration;
		}
		
		else {
		if(!empty($this->fb_registration)){
			$this->autoRender = false;
			$user=$this->User->find_user(array('fb_user'=>$this->fb_registration));
			//trying to register an existing user
			if(!empty($user)){
				if(empty($uesr['User']['facebook_id'])) {
				$this->User->id=$user['User']['id'];
				$user_id=$this->Connect->get_userid();
				$this->User->saveField('facebook_id',$user_id);
				}
				$this->request->params['requested']=1;
					$this->request->data=$user;
					$this->request->data['User']['username']=array('User.id'=>$user['User']['id']);
					//$this->request->data['User']['password']='temp';
					if (!$this->Session->check('redirect')){
					
						$this->Session->write('redirect',"/");
					}
					
					$this->Auth->authenticate['all']['fields']['password']='facebook_id';
					//$this->Auth->fields = array(
					//	'username' => 'username',
					//	'password' => 'facebook_id');
					$this->login();
					exit;
				
			}
			//we will add it as new user
			else{
				$this->User->create();
				$this->request->data=array();
				$this->request->data['User']['email'] = $this->fb_registration['registration']['email'];
				$this->request->data['User']['username'] = $this->fb_registration['registration']['email'];
				$this->request->data['User']['role_id'] = 2; // Registered
				$name=(!empty($this->fb_registration['registration']['name']))?$this->fb_registration['registration']['name']:"";
				$this->request->data['User']['name'] = $name;
				$this->request->data['User']['facebook_id'] = $this->fb_registration['user_id'];
				$this->request->data['User']['facebook_status'] = 1;
				$this->request->data['User']['status'] = 1;
				if (empty($this->fb_registration['registration']['first_name']) || empty($this->fb_registration['registration']['last_name'])){
					$name=explode(' ', $name);    
					$last = end($name);
					$name=$name[0];
				}
				else {
					$name=$this->fb_registration['registration']['first_name'];
					$last=$this->fb_registration['registration']['last_name'];
				}
				$this->request->data['Contact']['name']= $name;
				$this->request->data['Contact']['last']= $last;
				$this->request->data['Contact']['email']=$this->fb_registration['registration']['email'];
           // pr($this->request->data);
           // exit;
			//if (true) {
			if ($this->User->saveAll($this->request->data)) {
					//orly added
					$user_id=$this->User->getInsertID();
					unset($this->Auth->authenticate['all']['scope']['User.status']);
					$this->Session->setFlash(__( 'You have successfully registered an account. '), 'default', array('class' => 'success'));
					$this->request->params['requested']=1;
					$this->autorender=false;
					$this->request->data['User']['username']=array('User.id'=>$user_id);
					$this->request->data['User']['password']='temp';
					$this->login();
					exit;
				} else {
					CakeLog::write('debug',"add user failed: validation errors: ". json_encode ($this->User->validator()->errors()));
					Croogo::dispatchEvent('Controller.Users.registrationFailure', $this);
					$this->Session->setFlash(__( 'Please register first.'), 'default', array('class' => 'error'));
				}
			}
		}
	}
		$this->render('/Users/add');
	}
	public function add() {
		$failed=false;
		$this->set('title_for_layout', __( 'Register'));
        $AuthRedirect=($this->Session->check('redirect'))?$this->Session->read('redirect'):false;
		if (!empty($this->request->data)) {
			$this->User->create();
			//let registration login to continue with flow just this once, when user is added although status is not yet active, we need it to enable the flow of register and right after that add new group
			$this->request->data['User']['role_id'] = 2; // Registered
			$this->request->data['User']['activation_key'] = md5(uniqid());
			// need to activate for creators og group
			$this->request->data['User']['status'] =($this->Session->check('redirect'))? 1 : 0;
			$this->request->data['User']['username'] = (empty($this->request->data['User']['username']))?
				$this->request->data['User']['email'] : htmlspecialchars($this->request->data['User']['username']);
			$name = htmlspecialchars($this->request->data['User']['name']);
            $this->request->data['User']['name']=$name;
             $name=explode(' ', $name);    
             	$last = end($name);
                $name=$name[0];
				if(empty($name)) $name="ל";
            $this->request->data['Contact']['name']=$name;
            $this->request->data['Contact']['last']=$last;
            $this->request->data['Contact']['email']=$this->request->data['User']['email'];
           
			//if (true) {
			if ($this->User->saveAll($this->request->data)) {
				Croogo::dispatchEvent('Controller.Users.registrationSuccessful', $this);
				$user=$this->request->data['User'];
				if (strpos(HOST_NAME,'localhost')) $this->request->data['User']['email']='or.reznik@gmail.com';
				if($this->request->data['User']['status']<1){
					$ret=$this->_sendEmail(
						array(Configure::read('Site.title'), $this->_getSenderEmail()),
						$this->request->data['User']['email'],
						Configure::read('Site.title').' Account activation',
						'Users.register',
						'user activation',
						$this->theme,
						array('user' => $this->request->data)
					);
					
					if ($ret ||strpos(HOST_NAME,'localhost')){
						$this->Session->setFlash(__('You have successfully registered an account. An email has been sent with further instructions.'), 'default', array('class' => 'info'));
						$this->redirect("/aregister");
					}
					else {
						//we need to delete this user, becuase otherwize the username would be blocked
						$id=$this->User->getInsertID();
						$this->User->Delete($id);
						$this->Session->setFlash(__( 'Failed to send email. Please try a different email account'), 'default', array('class' => 'error'));
						$failed=true;
					}
				}
				if (!$failed){
						$this->_internal_login();
						exit;
				}
			} 
			//Registration failed
			else {
				Croogo::dispatchEvent('Controller.Users.registrationFailure', $this);
				if(isset($this->User->validationErrors['username'])) {
					$this->User->validationErrors['email']=$this->User->validationErrors['username'];
					unset($this->User->validationErrors['username']);
				}
					CakeLog::write('debug', 'validation errors for registration failure:'.json_encode($this->User->validationErrors));
				CakeLog::write('debug', 'validation errors for registration failure:'.json_encode($this->request->data));
				$this->Session->setFlash(__( 'The User could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		//
	//for first time and failures
	$this->fb_user = $this->Connect->user();
	$this->fb_registration = $this->Connect->registrationData();
	$this->set('fb_user',$this->fb_user);
	}

/**
 * Activate
 *
 * @param string $username
 * @param string $key
 * @return void
 * @access public
 */
 //expects all user inforation needed in $this->request->data
 private function _internal_login(){
 $this->request->params['requested']=1;

					$this->login();
					exit;
 
}
	public function activate($username = null, $key = null) {
		if ($username == null || $key == null) {
			$this->redirect(array('action' => 'login'));
		}
		if(true)
	/*	if ($this->User->hasAny(array(
				'User.username' => $username,
				'User.activation_key' => $key,
				'User.status' => 0,
			))) */
			{
			$user = $this->User->findByUsername($username);
			$this->User->id = $user['User']['id'];
			$this->User->saveField('status', 1);
			$this->User->saveField('activation_key', md5(uniqid()));
			Croogo::dispatchEvent('Controller.Users.activationSuccessful', $this);
			$this->Session->setFlash(__('Account activated successfully.'), 'default', array('class' => 'success'));
		} else {
			Croogo::dispatchEvent('Controller.Users.activationFailure', $this);
			$this->Session->setFlash(__( 'An error occurred.'), 'default', array('class' => 'error'));
		}
		$this->redirect(array('action' => 'login'));
		
	}

/**
 * Edit
 *
 * @return void
 * @access public
 */
	public function edit() {
	}

/**
 * Forgot
 *
 * @return void
 * @access public
 */
	public function forgot() {
		$this->set('title_for_layout', __( 'Forgot Password'));

		if (!empty($this->request->data) && isset($this->request->data['User']['username'])) {
			$user = $this->User->findByUsername($this->request->data['User']['username']);
			if (!isset($user['User']['id'])) {
				$this->Session->setFlash(__( 'Invalid username.'), 'default', array('class' => 'error'));
				$this->redirect(array('action' => 'login'));
			}

			$this->User->id = $user['User']['id'];
			$activationKey = md5(uniqid());
			$this->User->saveField('activation_key', $activationKey);
			$this->set(compact('user', 'activationKey'));

			$emailSent = $this->_sendEmail(
				array(Configure::read('Site.title'), $this->_getSenderEmail()),
				$user['User']['email'],
				 Configure::read('Site.title').' Reset Password',
				'Users.forgot_password',
				'reset password',
				$this->theme,
				compact('user','activationKey')
			);

			if ($emailSent) {
				$this->Session->setFlash(__( 'An email has been sent with instructions for resetting your password.'), 'default', array('class' => 'info'));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__( 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
			}
		}
	}

/**
 * Reset
 *
 * @param string $username
 * @param string $key
 * @return void
 * @access public
 */
	public function reset($username = null, $key = null) {
	
		$this->set('title_for_layout', __( 'Reset Password'));

		if ($username == null || $key == null) {
			$this->Session->setFlash(__( 'An error occurred.'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'login'));
		}

		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.username' => $username,
				'User.activation_key' => $key,
			),
		));
		
		if (!isset($user['User']['id'])) {
			$this->Session->setFlash(__( 'An error occurred.'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'login'));
		}

		if (!empty($this->request->data) && isset($this->request->data['User']['password'])) {
			$this->User->id = $user['User']['id'];
			$user['User']['activation_key'] = md5(uniqid());
			$user['User']['password'] = $this->request->data['User']['password'];
			$user['User']['verify_password'] = $this->request->data['User']['verify_password'];
			$options = array('fieldList' => array('password', 'verify_password', 'activation_key'));
			if ($this->User->save($user['User'], $options)) {
				$this->Session->setFlash(__( 'Your password has been reset successfully.'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__( 'An error occurred. Please try again.'), 'default', array('class' => 'error'));
			}
		}

		$this->set(compact('user', 'username', 'key'));
	}


/**
 * Logout
 *
 * @return void
 * @access public
 */
	public function logout() {
		Croogo::dispatchEvent('Controller.Users.beforeLogout', $this);
		$this->Session->setFlash(__('Logged out successfuly.'), 'default', array('class' => 'success'));
		Croogo::dispatchEvent('Controller.Users.adminLogoutSuccessful', $this);
		$this->redirect($this->Auth->logout());
		Croogo::dispatchEvent('Controller.Users.afterLogout', $this);
	}

/**
 * View
 *
 * @param string $username
 * @return void
 * @access public
 */
	public function view($username = null) {
		if ($username == null) {
			$username = $this->Auth->user('username');
		}
		$user = $this->User->findByUsername($username);
		if (!isset($user['User']['id'])) {
			$this->Session->setFlash(__d('croogo', 'Invalid User.'), 'default', array('class' => 'error'));
			$this->redirect('/');
		}

		$this->set('title_for_layout', $user['User']['name']);
		$this->set(compact('user'));
	}

	protected function _getSenderEmail() {
		return 'croogo@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
	}
	/**
	 * Admin login
	 *
	 * @return void
	 * @access public
	 */
	public function admin_login() {
		$this->set('title_for_layout', __d('croogo', 'Admin Login'));
		$this->layout = "admin_login";
		if ($this->request->is('post')) {
			Croogo::dispatchEvent('Controller.Users.beforeAdminLogin', $this);
			if ($this->Auth->login()) {
				Croogo::dispatchEvent('Controller.Users.adminLoginSuccessful', $this);
				$this->redirect($this->Auth->redirect());
			} else {
				Croogo::dispatchEvent('Controller.Users.adminLoginFailure', $this);
				$this->Auth->authError = __d('croogo', 'Incorrect username or password');
				$this->Session->setFlash($this->Auth->authError, 'default', array('class' => 'error'), 'auth');
				$this->redirect($this->Auth->loginAction);
			}
		}
	}
	
	/**
	 * Admin logout
	 *
	 * @return void
	 * @access public
	 */
	public function admin_logout() {
		Croogo::dispatchEvent('Controller.Users.adminLogoutSuccessful', $this);
		$this->Session->setFlash(__( 'Logged out successfuly.'), 'default', array('class' => 'success'));
		$this->redirect($this->Auth->logout());
	}
	
	/**
	 * Index
	 *
	 * @return void
	 * @access public
	 */
	public function index() {
		$this->set('title_for_layout', __( 'Users'));
	}
	
	/**
	 * Convenience method to send email
	 *
	 * @param string $from Sender email
	 * @param string $to Receiver email
	 * @param string $subject Subject
	 * @param string $template Template to use
	 * @param string $theme Theme to use
	 * @param array  $viewVars Vars to use inside template
	 * @param string $emailType user activation, reset password, used in log message when failing.
	 * @return boolean True if email was sent, False otherwise.
	 */
	protected function _sendEmail($from, $to, $subject, $template, $emailType, $theme = null, $viewVars = null) {
		if (is_null($theme)) {
			$theme = $this->theme;
		}
		$success = false;
	
		try {
			$email = new CakeEmail();
			$email->from($from[1], $from[0]);
			$email->to($to);
			$email->subject($subject);
			$email->template($template);
			$email->viewVars($viewVars);
			$email->theme($theme);
			$success = $email->send();
			
		} catch (SocketException $e) {
			$this->log(sprintf('Error sending %s notification : %s', $emailType, $e->getMessage()));
		}
		return $success;
		
	}
	private function _redirectafterlogin(){
		$redirect="";
		if($this->Session->check('Auth.redirect')) $redirect=$this->Session->read('Auth.redirect');
		CakeLog::write('debug',"redirect1 ". $redirect);

		if($this->Session->check('redirect')) $redirect=$this->Session->read('redirect');
		CakeLog::write('debug',"redirect1 ". $redirect);
		if ($redirect <> ""){
			$this->Session->delete('Auth.redirect');
			$this->Session->delete('redirect');
		}
		else
			$redirect = $this->Auth->loginRedirect;
		return $redirect;
	}
function after_register(){
}
}

