<?php 
/*************************************************** 
* Upload Component 
* 
* Manages uploaded files to be saved to the file system. 
* 
* @author       Tim Joyce
* @company      Thoughtwire media
* @version      1.0.1
*
* PARAMS *
*
$this->Upload->uploadFile(
    array(
        'filepath' => 'c:\wamp\www\myapp\webroot\\',                        //USE THIS PATH FOR LOCALHOST | CHANGE TO RELATIVE PATH WHEN APP GOES LIVE
        'directory' => $this->Auth->user('id'),                             //INIT THIS INDEX IF YOU ARE CREATING USER/SPECIFIC FOLDERS THAT CONTAIN THEIR FILES
        'tmp_filename' => $this->data['Image']['filename']['tmp_name'],     //MODEL[Image] | FORM FIELD NAME['filename'] | DO NOT CHANGE['tmp_name']
        'target_filename' => $this->data['Image']['filename']['name']       //MODEL[Image] | FORM FIELD NAME['filename'] | DO NOT CHANGE['name']
    )
);
*
***************************************************/
 
App::uses('Component', 'Controller');

class UploadComponent extends Component {
 
	
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
	public function startup(Controller $controller) {
		$this->user_id=($this->Auth->User('id'))?$this->Auth->User('id'):false;
	}
    function uploadFile($fileAttributes){
        //CHANGE THE FOLLOWING 2 VARS FOR CUSTOM SETTINGS 
        $allowed_filetypes = array('.jpg','.gif','.bmp','.png'); // ALLOWED FILE TYPES.
        $max_filesize = 16777216; // MAX FILESIZE IN BYTES [2mb].
         
        $filename = $fileAttributes['target_filename']; 
        $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); 
        $base_dir = $fileAttributes['filepath']; 
        $tmp_filename = $fileAttributes['tmp_filename']; 
        $target_filename = $fileAttributes['target_filename'];
        $final_target_path = $fileAttributes['filepath'] . "/";
        $allowed_filetypes= isset($fileAttributes['extensions'])?$fileAttributes['extensions']:$allowed_filetypes;
        if(!in_array($ext,$allowed_filetypes)) {
          return __('The file you attempted to upload is not allowed.');
        }
           
        if(filesize($fileAttributes['tmp_filename']) > $max_filesize){
            return __('The file you attempted to upload is too large.');  
        }
         
        if(isset($fileAttributes['directory'])){
            $build_directory = $fileAttributes['directory'];
            $build_directory = $base_dir.$build_directory;
            $final_target_path = $build_directory . "/";
            if(!file_exists($build_directory)){
                if(!mkdir($build_directory, 0777)) {
                  return __('There was a problem with the upload destination.');      
                }
            }
        }
        $final_target_path = $final_target_path.$target_filename;
        
        if(move_uploaded_file($tmp_filename, $final_target_path)) {
		$return=array('filename'=>$final_target_path);
             return $return;
        } else {
             return __("There was an error during your file upload");
        }
    }
} 
?>