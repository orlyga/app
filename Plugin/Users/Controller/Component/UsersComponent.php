<?php

/**
 * Users Component
 *
 * @package Croogo.Users.Controller.Component
 * 
 * 
 */
App::uses('Component', 'Controller');


class UsersComponent extends Component {
	public $UserModel;
	public $user_id;


/**
 * initialize
 *
 * @param Controller $controller instance of controller
 */
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->request = $controller->request;
		$this->response = $controller->response;
		$this->Auth = $controller->Auth;
		$this->UserModel=ClassRegistry::init('Users.User');
		
	}

/**
 * Startup
 *
 * @param object $controller instance of controller
 * @return void
 */
	public function startup(Controller $controller) {
		$this->user_id=($this->Auth->User('id'))?$this->Auth->User('id'):false;
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


}
