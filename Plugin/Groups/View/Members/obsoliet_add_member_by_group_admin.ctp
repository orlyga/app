<?php echo $this->Html->script('jquery.validate');
?>
<script>
    function afterContactCheckExist(data){
// $("#GroupsUserMemberContactId").val($(data).find("#ContactId").val());
 }
function set_member_type(mem_type){
	if (mem_type=='child-member'){
		$('#tab-3').removeClass('hide');
		$('#tab-3 input[required]').each(function(){
			$(this).attr('required','required');
		});
		$("#GroupsUserMemberContactName").attr('placeholder','<?php echo __('Parent Name')?>')
	}
	if (mem_type!='child-member'){
		$('#tab-3').addClass('hide');
		$('#tab-3 input[required]').each(function(){
			$(this).removeAttr('required');
		});
		$("#GroupsUserMemberContactName").attr('placeholder','<?php echo __('Name')?>')
	}
}
$(function(){
$("input[name$='[last]']").change(function() {
	   var $last = $(this).closest('form').find("input[name$='[last]']").val();
	   $("#GroupsUserMemberChildContactLast").val($last); 
});
});
function submitAddForm(ev){
	//ev.preventDefault();
	ev.target.checkValidity();
	//return false;
}
</script>
<h3><?php echo __('Add Member to the group')?></h3>
<div class="member form">
	<?php echo $this->croogoForm->create('GroupsUser',array('type'=>'file'));?>

<?php

echo $this->element("Groups.selectMemberType",array('parent'=>"GroupsUser.Member",'default'=>"child-member",'by_group_admin'=>true));
echo $this->element("Users.setUserData",array('parent'=>"GroupsUser",'user_id'=>null,'group_id'=>null));

//echo $this->croogoForm->input('GroupsUser.Member.Parent.Contact.name',array('placeholder'=>__('First Parent Name')));
echo $this->element("Contacts.form",array('parent'=>"GroupsUser.Member",'contact_type'=>'parent-nouser'));
?><div id='tab-3'><?php
echo $this->element("Contacts.form",array('parent'=>"GroupsUser.Member.Child",'contact_type'=>'child-member-no-parent'));
?></div><?php
//echo $this->element('Contacts.AddEditAdult', array('contact_type'=>'staff','parent'=>"GroupsUser.Member",));

?>

<?php
		 echo  $this->croogoForm->end('Send',array('div'=>array('class'=>'btn green_gradient')));
?>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular-strap/0.7.4/angular-strap.min.js"></script>



