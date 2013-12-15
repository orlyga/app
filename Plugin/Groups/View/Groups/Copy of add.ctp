<?php echo $this->Html->script('jquery.validate');
?>
<script>
function add_step(div_id){
	$('#'+div_id).removeClass('hide');
}
function after_add_child(){
submitAddForm();
}

function submitAddForm(ev){
	//ev.preventDefault();
	ev.target.checkValidity();
}
</script>

<div class="groups form">
	<?php echo $this->croogoForm->create('Group',array('type'=>'file'));
 echo $this->croogoForm->input('redirect',array('type'=>'hidden','value'=>'/groups/members/add_member_admin'));
 echo $this->element('Groups.group_form',array('parent'=>'Group'));
$this->Form->unlockField('Member.user_id');
$this->croogoForm->unlockField('Group.user_id');
$this->croogoForm->unlockField('Group.contact_id');
$this->croogoForm->unlockField('Contact.email');
echo  $this->croogoForm->end('Continue',array('div'=>array('class'=>'btn green_gradient')));

// echo $this->Html->link(__('Continue'), 'javascript:next_step()',array('id'=>'SaveForm','class'=>'btn green_gradient'));


echo '</div>';

//echo $this->element("Groups.selectMemberType",array('parent'=>"GroupsUser.1.Member"));
//echo $this->element("Users.setUserData",array('parent'=>"GroupsUser.1",'user_id'=>$contact['User']['id'],'group_id'=>null));
//echo $this->element("Groups.SelectContactChild",array('parent'=>"GroupsUser.1.Member",'contact_parent_id'=>$contact['User']['Contact']['id']));

?>

<?php

 
		//  echo  $this->croogoForm->end('Continue',array('div'=>array('class'=>'btn green_gradient')));
?>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular-strap/0.7.4/angular-strap.min.js"></script>



