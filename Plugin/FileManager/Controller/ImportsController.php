<?php

App::uses('FileManagerAppController', 'FileManager.Controller');
App::import('Vendor', 'excel_reader2'); //import statement


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
class ImportsController extends FileManagerAppController {

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	var $name ='Imports';
	var $group_id=null;
	var $default_city=0;
	var $sendmail=true;
	var $sendtext="";
/**
 * Helpers used by the Controller
 *
 * @var array
 * @access public
 */
	var $helpers = array('Js');
	var $components=array('Croogo.Upload','Email');
	var $uses=array('Groups.Group','Contacts.ContactsRelation','Groups.GroupsUser','Contacts.Contact');
	
	
	public function beforeFilter() {
		$this->Auth->allow();
		if((in_array($this->action,array('importgroup')))&&(!empty($this->request->data)))
			$this->request->params['requested']=1;
		parent::beforeFilter();
			
	}
	private function _set_error($err_message){
		$this->Session->setFlash(__d('croogo', $err_message), 'default', array('class' => 'error'));
		$this->render();
		
	}
	function importgroup() {
		
			//sent to printing
		if($this->Session->check('Group.Group'))
			$this->group_id=$this->Session->read('Group.Group');
		else
		$this->_set_error("Please select the group");
			$this->default_city=$this->GroupsUser->Group->getGroupCity($this->group_id);
		if (!isset($this->request->data['ImportFile']['import_file'])){
			$file_name=$this->Upload->uploadFile(
					array(
							'filepath' => WWW_ROOT,                        //USE THIS PATH FOR LOCALHOST | CHANGE TO RELATIVE PATH WHEN APP GOES LIVE
							'directory' => 'uploads'.DS.'pdf',                             //INIT THIS INDEX IF YOU ARE CREATING USER/SPECIFIC FOLDERS THAT CONTAIN THEIR FILES
							'tmp_filename' => $this->request->data['ImportFile']['pdf_file']['tmp_name'],     //MODEL[Image] | FORM FIELD NAME['filename'] | DO NOT CHANGE['tmp_name']
							'target_filename' => $this->request->data['ImportFile']['pdf_file']['name']  ,     //MODEL[Image] | FORM FIELD NAME['filename'] | DO NOT CHANGE['name']
							'extensions'=>array('.pdf','.doc','.docx')
					)
			);
		if (!is_array($file_name)) $this->_set_error($file_name);			//save file and send me an email 
			
		}
		$file_name=$this->Upload->uploadFile(
				array(
						'filepath' => WWW_ROOT,                        //USE THIS PATH FOR LOCALHOST | CHANGE TO RELATIVE PATH WHEN APP GOES LIVE
						'directory' => 'uploads'.DS.'excel',                             //INIT THIS INDEX IF YOU ARE CREATING USER/SPECIFIC FOLDERS THAT CONTAIN THEIR FILES
						'tmp_filename' => $this->request->data['ImportFile']['import_file']['tmp_name'],     //MODEL[Image] | FORM FIELD NAME['filename'] | DO NOT CHANGE['tmp_name']
						'target_filename' => $this->request->data['ImportFile']['import_file']['name'] ,     //MODEL[Image] | FORM FIELD NAME['filename'] | DO NOT CHANGE['name']
						'extensions'=>array('.xls','.xlt','.xla','.xlsx')
				)
		);
		if (!is_array($file_name)) $this->_set_error($file_name);
		$file_name=$file_name['filename'];
		$this->sendmail=$this->request->data['ImportFile']['SendEmail'];
		$this->sendtext=$this->request->data['ImportFile']['emailtext'];
			$data = new Spreadsheet_Excel_Reader($file_name, true);
			$headings = array();
			$xls_data = array();
			$text_err="";
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
				//this is the headings row, each column (j) is a header
				$headings[$data->sheets[0]['cells'][1][$j]] =$j ;
			}
	$total_row=$data->sheets[0]['numRows'];
	$good_rows=0;
			for ($i = 3; $i <= $total_row; $i++) {
				//echo '<div style="background-color:yellow;"> :שורה  '.($i-2).'</div>';
				$this->final=array();
				$child=false;
				$row=array();
				$row_data = $data->sheets[0]['cells'][$i];
				$text=$this->_ImportsetMember($row_data,$headings,$i);
				if ($text<>"") $text_err.=$text.'<hr>';
				else $good_rows++;
				}
				$total_row=$total_row-2;
				$this->set(compact('text_err','total_row','good_rows'));
	}
	
	private function _ImportsetMember($row_data,$headings,$i){
		
///////////////Set CONtact Information//////////////////////////////////		
		if(!isset($row_data[$headings['name']])||
				!isset($row_data[$headings['last']])||
				!isset($row_data[$headings['first']]))
		{
			return "<b> שורה ".$i." שם הילד או ההורה חסר, או שם המשפחה חסר </b> ערך לא יכול להיות ריק, שורה לא נשמרה.<br/>";
		}
		if(!isset($row_data[$headings['email']]))
		{
			return "<b>שורה ".$i." אי מייל חסר </b> ערך לא יכול להיות ריק, השורה לא נשמרה.<br/>";
			
		}
		$first_cell=isset($row_data[$headings['first_cell']])?$row_data[$headings['first_cell']]:"";
		$first_cell=($first_cell<>"") ? ereg_replace("[^0-9]", "", $first_cell ):"";
		$second_cell=isset($row_data[$headings['second_cell']])?$row_data[$headings['second_cell']]:"";
		$second_cell=($second_cell<>"") ? ereg_replace("[^0-9]", "", $second_cell ):"";
		$email=isset($row_data[$headings['email']])?$row_data[$headings['email']]:"";
		if($email<>""){
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				return "<b>: שורה ".$i." </b> אי מייל לא תקין ".$email."<br/>";
			}
		}
		$row['ContactsRelation']['Parent']['name']=$row_data[$headings['first']];
		$row['ContactsRelation']['Parent']['last']=$row_data[$headings['last']];
		$row['ContactsRelation']['Parent']['email']=$email;
		$row['ContactsRelation']['Parent']['cellphone']=$first_cell;
		$row['ContactsRelation']['Parent']['city_id']=$this->default_city;
		
		$row['ContactsRelation']['Contact']['email']=$email;
		$row['ContactsRelation']['Contact']['address']=isset($row_data[$headings['address']])? $row_data[$headings['address']]:NULL;
		$row['ContactsRelation']['Contact']['name']=$row_data[$headings['name']];
		$row['ContactsRelation']['Contact']['last']=$row_data[$headings['last']];
		$row['ContactsRelation']['Contact']['phone'] = isset($row_data[$headings['home_phone']]) ? ereg_replace("[^0-9]", "", $row_data[$headings['home_phone']] ):'';
		if (isset($row_data[$headings['birthdate']])) {
		
			$bd=$row_data[$headings['birthdate']];
			 $bd=date('Y-m-d', strtotime($bd));
			$row['ContactsRelation']['Contact']['birth_date']=$bd;
		}
		$row['ContactsRelation']['Contact']['gender']=isset($row_data[$headings['gender']])? $row_data[$headings['gender']] :NULL;
		$row['ContactsRelation']['Contact']['city_id']=$this->default_city;
		$contact=$this->ContactsRelation->setNewContantandChild($row,true);
		if(!$contact){
			$err=$this->ContactsRelation->getError();
			return  "<b>: שורה ".$i." </b> ".$err;
		}
		//add second parent
		if(isset($row_data[$headings['second']])){
				$sec['ContactsRelation']['Parent']['name']=$row_data[$headings['second']];
				$sec['ContactsRelation']['Parent']['last']=$row_data[$headings['last']];
				$email_sec=isset($row_data[$headings['second_email']])?$row_data[$headings['second_email']]:"";
				if($email_sec<>""){
					if(!filter_var($email_sec, FILTER_VALIDATE_EMAIL))
					{
						$err= "אזהרה: <b>: שורה ".$i." </b> אי מייל לא תקין ".$email_sec."<br/>";
					}
				}
				$sec['ContactsRelation']['Parent']['email']=$email_sec;
				$sec['ContactsRelation']['Parent']['cellphone']=$second_cell;
				$sec['ContactsRelation']['Parent']['city_id']=$this->default_city;
				$sec['ContactsRelation']['relation_type']="second-parent";
				$sec['ContactsRelation']['contact_id']=$contact;
				$ret=$this->ContactsRelation->setParent($sec);
				if(!$ret){
					$err=$this->ContactsRelation->getError();
					return  "<b>: שורה ".$i." </b> ".$err;
					}
		}
/////////////////SET Group User info/////////////////////////////////////////////////		
		$gu['GroupsUser']['Member']['member_type']='child-member';
		$gu['GroupsUser']['Member']['status']=0;
		$gu['GroupsUser']['Member']['activation_key']=md5(uniqid());
		$gu['GroupsUser']['Member']['contact_id']=$contact;
		$gu['GroupsUser']['group_id']=$this->group_id;
		$gu['GroupsUser']['role_id']=6;
		$user=$this->ContactsRelation->getUserbyContactChild($contact);
		if ($user)	$gu['GroupsUser']['user_id']=$user['Users']['id'];
		$ret=$this->GroupsUser->saveAll($gu,array('deep'=>true));
		if(!$ret){
			$err=$this->GroupsUser->getError();
			return  "<b>: שורה ".$i." </b> ".$err;
		}
		//send letter
		if (($email<>"") && $email_sec<>"") $email=array($email,$email_sec);
		if (($email=="") && $email_sec<>"") $email=$email_sec;
		
		if ($email<>""){
			if($this->sendmail){
			
				$group_name=$this->GroupsUser->Group->read('name',$this->group_id);
				$group_name=$group_name['Group']['name'];
				$this->request->data['Contact']=array('prefix_text'=>$this->sendtext,'group_name'=>$group_name,'activation_key'=>$gu['GroupsUser']['Member']['activation_key'],'name'=>$row_data[$headings['name']]);
				$this->Email->to      = $email;
				$this->Email->subject = htmlspecialchars($group_name);
				$this->Email->from    = Configure::read('Site.email');
				$this->Email->sendAs  = 'html'; 
				$this->Email->template="Groups.invite_member";
				$ret=$this->Email->send();
				if (!$ret) 
							return "<b>: שורה ".$i." </b> חבר הקבוצה הוסף אך הייתה בעייה בשליחת המייל אליו";

			}
		}
		else {
		return "<b>: שורה ".$i." </b> ".__("Missing Email, email invitation couldn't be saved");
		return "<b>: שורה ".$i." </b> ".__("Missing Email, email invitation couldn't be saved");

		}
		return "";
	}
	
	private function upload_file(){
		$file = $this->request->data['Import']['import_file'];
		if ($file['error'] === UPLOAD_ERR_OK) {
			$id = String::uuid().$file['name'];
			echo APP.'uploads'.DS.$id.$file;
			if (move_uploaded_file($file['tmp_name'], APP.'uploads'.DS.$id)) {
				return APP.'uploads'.DS.$id;
			}
		}
		return false;
	}
	private function _importfile($filename){
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if (!in_array($extension,array('doc','docx','xls','xls','xlt','xla','xlsx'))){
			$error=__('File should be Word or Excell');
			$active=0;
			$this->set(compact('error','active'));
			return false;
		}
		$data = $this->request->data['Import'];
		$data['import_file']=$this->upload_file();
		$data['type_id']='file';
		$this->redirectto="/";
		return $data;
		//$this->render('invite');
	}
	
	
}