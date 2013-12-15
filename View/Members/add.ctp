<!-- contacts/add.ctp -->
<div class="error-message"><?php if(isset($error)) echo $error;?></div>
<div id="addmember<?php echo $role_type?>"class="members form">
<?php
$email_ro=$cell_ro="";
$from_parent='0';
if(!isset($role_type)) $role_type ='member';
if(!empty($this->request->data)){
	$from_parent=isset($this->request->data['Member']['datafromparent'])?$this->request->data['Member']['datafromparent']:null;
	$email_ro=(!empty($this->request->data['Member']['Contact']['email']))?'readonly':'';
	$cell_ro=(!empty($this->request->data['Member']['Contact']['cellphone']))?'readonly':'';
}
 echo $this->Form->create('Member',array('type'=>'file','id'=>'addmember'.$role_type));
 echo $this->Form->input('Member.grouprole',array('value' => $role_type,'type' => 'hidden'));
  echo $this->Form->input('Member.grouprole_id',array('type' => 'hidden'));
 
echo $this->Form->input('Member.datafromparent',array('value' => $from_parent,'type' => 'hidden'));
	echo $this->Form->input('Member.request_status',array('type'=>'hidden','id'=>'request_status_id'));
	$submit=__("Send");
	$render= 'add';
	switch ($role_type){
	case 'master':
		$contact_fields=array(
		'email'=>array('type'=>'hidden','secure'=>'false'),
		'name'=>array('type'=>'hidden','secure'=>'false'),
		'last'=>array('type'=>'hidden','value'=>'Group'),
		'image'=>array('type'=>'hidden'),);
		break;
	case 'staff':
		$contact_fields=array(
		'address'=>array('type'=>'hidden','secure'=>'false'),
		'city'=>array('type'=>'hidden','value'=>0),
		'postcode'=>array('type'=>'hidden'),
		'phone'=>array('type'=>'hidden'),
		'cellphone'=>array('type'=>'display'),
		'image'=>array('show'=>'show'),
		'gender'=>array('type'=>'hidden'),
		'birthdate'=>array('type'=>'hidden'),
		'name'=>array('label'=>__("Name")));
		$render="index";
		$title=__('Add Staff Member');
		break;
	case 'head-staff':
	$contact_fields=array(
			'address'=>array('type'=>'hidden','secure'=>'false'),
		'image'=>array('type'=>'hidden'),
		'city'=>array('type'=>'hidden','value'=>0),
		'postcode'=>array('type'=>'hidden'),
		'phone'=>array('type'=>'hidden'),
		'cellphone'=>array('type'=>'display'),
		'image'=>array('show'=>'show'),
		'gender'=>array('type'=>'hidden'),
		'birthdate'=>array('type'=>'hidden'),
		'name'=>array('label'=>__("Name")));
		$render="index";
		$title=__('Add Head Staff Member');
		break;
		
case 'child-member':
	$contact_fields=array(
		'address'=>array('rows'=>2,'cols'=>17),
		'gender'=>array('type'=>'hidden'),
		'image'=>array('show'=>'show'),
		'cellphone'=>array('readonly'=>$cell_ro),
		'email'=>array('readonly'=>$email_ro));
		$submit=__("Next");
		$title=__('Add Child Member');
		break;
case 'member':
	$contact_fields=array(
	'address'=>array('rows'=>2,'cols'=>17),
		'image'=>array('type'=>'display'));
		$title=__('Add  Member');
default:
$contact_fields=array(
		'image'=>array('show'=>'show'));
		$title=__('Add Member');
		break;
		
}
echo '<h3>'.$title.'</h3>';
echo $this->element('contacts/form', array('parent'=>'Member','hostingdiv'=>'addmember'.$role_type,'fieldset'=>$contact_fields));
$this->Form->unlockField('Member.grouprole_id');
$this->Form->unlockField('Member.grouprole');

$this->Form->unlockField('Member.request_status');
$this->Form->unlockField('Member.datafromparent');




echo '<div id="submit_member" >';
	// echo  $this->Js->submit($submit,
//	  array( 'url'=> array('controller'=>'members',
//	   'action'=>'add',$role_type),
//	   'before'=>'var $this=$(this);',
//	   'id'=>'submit_add_member',
//	    'class' => 'ajax-link button-elegant',
//	    'buffer' => false,
//	    'success'=>'updateMember(data,"'.$role_type.'");',
//	    ));
echo '</div>';	  
echo  '</fieldset>';
 echo $this->Form->end(array('class' => 'ajax-link button-elegant','label' => __($submit),'id'=>'submit_add_member',));?>

</div>