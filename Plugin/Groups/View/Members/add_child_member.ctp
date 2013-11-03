<?php echo $this->Html->script('jquery.validation');?>

<script>
function check_child(){
	if ($("#add_child").validation()) {
		$("#ContactsRelationParentLast").val($("#ContactsRelationContactLast").val());
        $("#ContactsRelationParentCityId").val($("#ContactsRelationContactCityId").val());
		next_step(-1);
	}
}
function next_step(i){
	if(i<0) $('.carousel').carousel('next');
	else $('.carousel').carousel(i-1);
}
function click_add_child(){
		$("#add_child").find("input[xxx]").each(function( ){
		$(this).attr("required","required");
	});
	$("#add_child").find(".xxx").each(function( ){
		$(this).addClass('required');
	});

	next_step(-1);
}
function submit_add_member(){
	if($("#ContactsRelationParentCellphone").val()=="" && $("#ContactsRelationParentEmail").val()=="")
	  {
	  	$("#error_adult").html('<?php echo __('Please provide with cellphone or email'); ?>');
	  	return false;
	  }
		if($("#ontact-form").validation())
	$("#AddMemberForm").submit();
	
}
function child_selected(contact_id){
	setinputfield('ContactsRelationContactId',contact_id);
	submit_add_member();
}
$(function(){
	$('.carousel').carousel();
	$('.carousel').carousel('pause');
	if($('#add_child .error-message').length>0){
		$('#contact-form').removeClass('active');
		$('#add_child').addClass('active');
	}
	else {if($('#contact-form .error-message').length>0){
		$('#contact-form').addClass('active');
		$('#add_child').removeClass('active');
	}}
	
});
	</script>

<div class="row" style="padding-top:2%">
	<div class="span9">
<?php echo $this->croogoForm->create('GroupsUser',array('type'=>'file','id'=>'AddMemberForm'));?>

	<div id="myCarousel" class="carousel slide">
		
		<div class="carousel-inner ">
			
		
			<!-----------------Your Info----------------->
           
			<div id='contact-form' class="item <?php if ($member_type<>'child-member') echo 'active' ?>">
				<h3><?php if ($member_type<>'child-member') echo __('Add New Staff member'); else echo __('First Parent information'); ?></h3>
				
				
				<div id='error_adult' class='alert-error'></div>
				<?php 
				//check session for tyoe of group. different adult/staff
				if ($member_type=='child-member')
						echo $this->element('Contacts.AddEditAdult', array('contact_type'=>'parent','parent'=>'ContactsRelation.Parent',));
				else
					echo $this->element('Contacts.AddEditAdult', array('contact_type'=>'staff','parent'=>"GroupsUser.Member",));?>
 
					<?php
					$contact_id=(isset($contact['User']['Contact']['id']))? $contact['User']['Contact']['id']:null;
					 echo $this->element("Groups.SelectContactChild",array('parent'=>"ContactsRelation",'contact_parent_id'=>$contact_id));
					if((isset($children) && (!$children))||!isset($children)){?>
					<?php } ?>
					<div id="button-save-no-child" class='hide'>	
						<?php echo $this->html->link(__('Save and View Group'),'javascript:submit_add_member(false)'	,array('class'=>'btn green_gradient'));?>			
					</div>
				<div style="margin-top:2%">
					<?php  echo $this->html->link(__('Save and View Group'),'javascript:submit_add_member()'	,array('class'=>'btn green_gradient'));?>			
				</div>
			</div>
			<?php if($member_type=='child-member') {?>
					<!-----------------Add Child----------------->
					<div class="item active" id="add_child">
							<h3><?php echo __('Child information') ?></h3>
							<?php 
							$user_contact=(isset($contact['User']['Contact']['id']))? $contact['User']['Contact']['id']:null;
							$add_child=$this->element('Contacts.AddChild',array('parent'=>"ContactsRelation",$user_contact))	;
							if(isset($children) && $children) $add_child=str_replace("required","xxx",$add_child);?>
							<?php	echo $add_child;?>
							<div id="button-child-info" >
						 	<?php	echo $this->html->link(__('Update parents information'),'javascript:check_child();'	,array('class'=>'btn green_gradient'));?>			
							</div>
							
					</div>
					<?php }?>
		</div>
		<?php echo $this->Form->input('redirect',array('type'=>'hidden','value'=>'/groupsview'));
			echo $this->Form->input('GroupsUser.Member.member_type',array('type'=>'hidden'));
		 echo $this->Form->submit('submit',array('class'=>'hide'));
		echo $this->Form->end();?>
		<a class="left carousel-control " href="#myCarousel" data-slide="prev"><i class='icon-arrow-left'></i></a>
		<a class="right carousel-control " href="#myCarousel" data-slide="next"><i class='icon-arrow-right'></i></a>
	</div>
	</div>
  	
</div>






  	
