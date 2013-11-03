<?php

App::uses('FileManagerAppController', 'FileManager.Controller');

/**
 * Attachments Controller
 *
 * This file will take care of file uploads (with rich text editor integration).
 *
 * PHP version 5
 *
 * @category FileManager.Controller
 * @package  Croogo.FileManager.Controller
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class PrintoutsController extends FileManagerAppController {

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	var $name ='Printouts';
/**
 * Helpers used by the Controller
 *
 * @var array
 * @access public
 */
	public $helpers = array('Js','Acl.Acl');
	
	var $uses=array('Groups.GroupsUser');
	public function beforeFilter() {
		$this->Auth->allow(array('printpreview'));
		if((in_array($this->action,array('printpreview')))&&(!empty($this->request->data)))
			$this->request->params['requested']=1;
		parent::beforeFilter();
			
	}
function  check_print_flds(){
		if(empty($this->request->data['Print']['amount'])) {
			return  "<p class='error'><?php echo __('Please enter Copies number')?></p>";
		}
		
		if(empty($this->request->data['Print']['content'])) {
			return "<p class='error'><?php echo __('Message Content is empty')?></p>";
		}
		return "";
	}
	function view($body=""){
		$this->set(compact('body'));
		//$this->set('title_for_layout', __('Print'));
		$this->render('view');
	}
	
	function printgroup($group_id=null,$format="default"){
	if (!$group_id) {
			$group_id = $this->Session->read('Group.Group');
		}
		$group=$this->GroupsUser->Group->read(null,$group_id);
		$child_members=$this->GroupsUser->get_members_by_group($group_id,"child-member","Contact.name",0);
		
		$role_id=6;//doesnt really matter as long as there is some value
		$contact_id=0;//doesnt really matter as long as there is some value
		$this->set(compact('child_members','role_id','contact_id','group'));
	}
	
	
	function  printpreview(){
		$content_for_layout='No printing data';
		if(!empty($this->request->data) && isset($this->request->data['Import']['amount'])&& $this->request->data['Import']['amount']>0)
		{
			$i=1;
				$html="";
				while ($i<=$this->request->data['Import']['amount']){
					$html.='<div class="nicebox">';
					$html.=$this->request->data['Import']['Message'];
					$html.=	'</div><hr>';
					$i++;
					}
		$content_for_layout=$html;
		}
	$this->set(compact('content_for_layout'));
	$this->render();
}

}
