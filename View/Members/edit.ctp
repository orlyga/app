<!-- contacts/add.ctp -->
<div class="members form">
<?php

if (isset($this->params['named']['role_type'])) $role_type=$this->params['named']['role_type'];
	else if(!isset($role_type)) $role_type ='member';
 echo $this->Form->create('Member',array('type'=>'file','id'=>'addmember'.$role_type));?>
	<fieldset>
	<?php	
	 echo $this->Form->input('Member.grouprole_id',array('type' => 'hidden'));
	 	 echo $this->Form->input('Member.grouprole',array('value' => $role_type,'type' => 'hidden'));
	 
	echo $this->Form->input('Member.contact_id',array('type'=>'hidden','value'=>''));
	echo $this->Form->input('Member.group_id',array('type'=>'hidden',SessionComponent::read('Group.Id')
	));
	echo $this->Form->input('Member.user_id',array('type'=>'hidden'));
		echo $this->Form->input('Member.family_id',array('type'=>'hidden'));
	
	echo $this->Form->input('Member.request_status',array('type'=>'hidden','id'=>'request_status_id'));
	$submit=__("Send");
	$submit_show="";
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
		'image'=>array('type'=>'hidden'),
		'city'=>array('type'=>'hidden','value'=>4),
		'postcode'=>array('type'=>'hidden'),
		'phone'=>array('type'=>'hidden'),
		'name'=>array('label'=>__("Name")));
		$render="index";
		break;
	case 'head-staff':
	$contact_fields=array(
		'address'=>array('type'=>'hidden','secure'=>'false'),
		'image'=>array('type'=>'hidden'),
		'city'=>array('type'=>'hidden','value'=>4),
		'postcode'=>array('type'=>'hidden'),
		'phone'=>array('type'=>'hidden'),
		'name'=>array('label'=>__("Group Head Name")));
		$render="index";
		break;
case 'child-member':
	 echo $this->Form->input('Family.group_id',array('type' => 'hidden'));
	$contact_fields=array(
		'address'=>array('rows'=>2,'cols'=>17),
		'image'=>array('type'=>'display'));
		$submit=__("Parents");
		$submit_show='style="display:none"';
		break;
default:
$contact_fields=array(
		'image'=>array('type'=>'display'));
		break;
		
}
echo $this->element('contacts/form', array('fieldset'=>$contact_fields));
$this->Form->unlockField('Member.group_id');
$this->Form->unlockField('Member.family_id');
$this->Form->unlockField('Member.user_id');

echo '<div id="submit_member" >';
$this->Form->unlockField('Contact.city_id');
	 echo  $this->Js->submit($submit,
	  array( 'url'=> array('controller'=>'members',
	   'action'=>'add','render'=>$render),
	   'before'=>'var $this=$(this);',
	    'class' => 'ajax-link button-elegant',
	    'buffer' => false,
	    'success'=>'updateMember(data);',
	    ));
echo '</div>';	  
echo  '</fieldset>';
 echo $this->Form->end();?>

</div>