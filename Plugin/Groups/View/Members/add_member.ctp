<?php echo $this->Html->script('jquery.validation');
$group_vars=$this->Group->setVarsbyGroupType($group_type);
?>

<script>

function next_step(i){
	if(i<0) $('.carousel').carousel('next');
	else $('.carousel').carousel(i-1);
}

function submit_add_member(){
	
	if($("#contact-form").validation())
	$("#AddMemberForm").submit();
	
}

</script>
<div class="row" style="padding-top:2%">
	<div class="span9">
		<?php echo $this->croogoForm->create('GroupsUser',array('type'=>'file','id'=>'AddMemberForm'));?>
		<div id='contact-form' >
			<?php 
			if($member_type=='head-staff')	{echo '<h3>'.__('Add %s Information',__($group_vars['head_staff_type'])).'</h3>';}
			if ($member_type=='staff') echo '<h3>'.__('Add %s Information',__('Staff')).'</h3>';
			if ($member_type=='child-member') echo '<h3>'.__('Add %s Information',__('Member')).'</h3>';?>
									<div id='error_adult' class='alert-error'></div>
				<?php 
				//check session for tyoe of group. different adult/staff
                    if (isset($this->request->data['GroupsUser']['Member']))
					    echo $this->element('Contacts.AddEditAdult', array('contact_type'=>$member_type,'parent'=>"GroupsUser.Member",));
					else
					    echo $this->element('Contacts.AddEditAdult', array('contact_type'=>$member_type,));?>
					
					<div id="button-save-no-child" >	
						<?php echo $this->html->link(__('Save and View Group'),'javascript:submit_add_member(false)'	,array('class'=>'btn green_gradient'));?>			
					</div>
			</div>
			
		</div>
		<?php echo $this->Form->input('redirect',array('type'=>'hidden','value'=>'/groupsview'));
			echo $this->Form->input('GroupsUser.Member.member_type',array('value'=>$member_type,'type'=>'hidden'));
		 echo $this->Form->submit('submit',array('class'=>'hide'));
		echo $this->Form->end();?>
	</div>
	






  	
