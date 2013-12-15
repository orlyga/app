<!-- contacts/add.ctp -->
<script>
$(function() {
	$("#GroupsUserEditForm :input").change(function() {
	   $("#GroupsUserEditForm").data("changed",true);
	});
});
function edit_parents(){
	if ($("#GroupsUserEditForm").data("changed")) {
		alert ("hi");
  		$("#redirectid").val('<?php echo $this->html->url(array('pulgin'=>'contacts','controller'=>'contacts','action' => 'edit_contact_relations','contact_id'=>$this->request->data['Contact']['id']));?>');
  }
  else
  {
  	window.location='<?php echo $this->html->url(array('pulgin'=>'contacts','controller'=>'contacts','action' => 'edit','contact_id'=>$this->request->data['Contact']['id']));?>';

  }

	
}
</script>
<?php echo $this->Html->link(__('Edit Parents'), 'javascript:edit_parents()',array('class'=>'btn green_gradient'));
?>
<div class="members form">
<?php
 echo $this->Form->create('GroupsUser',array('type'=>'file'));?>
	<?php	
	$role_type=$this->request->data['Member']['member_type'];
	echo $this->Form->input('Member.contact_id',array('type'=>'hidden','value'=>''));
	echo $this->Form->input('Member.request_status',array('type'=>'hidden','id'=>'request_status_id'));
	echo $this->Form->input('redirect',array('type'=>'hidden','id'=>'redirectid'));
	
	$submit=__("Send");
	$submit_show="";
	$render= 'add';
	switch ($role_type){
	
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
	// echo $this->Form->input('Family.group_id',array('type' => 'hidden'));
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
echo $this->element('Contacts.form', array('fieldset'=>$contact_fields));

$this->Form->unlockField('Contact.city_id');
	 		 echo  $this->Form->submit(__('Submit'),array('class'=>'btn green_gradient'));
			 echo $this->Form->end();

?>

