<!-- contacts/add.ctp -->
<script>
$(function() {
	$("#GroupsUserEditForm :input").change(function() {
	   $("#GroupsUserEditForm").data("changed",true);
	});
});
function edit_parents(){
	if ($("#GroupsUserEditForm").data("changed")) {
  		$("#redirectid").val('<?php echo $this->html->url(array('plugin'=>'contacts','controller'=>'contacts','action' => 'edit_contact_relations','contact_id'=>$this->request->data['Contact']['id'],'contact_type'=>'parent'));?>');
  }
  else
  {
  	window.location='<?php echo $this->html->url(array('pulgin'=>'contacts','controller'=>'contacts','action' => 'edit','contact_id'=>$this->request->data['Contact']['id'],'contact_type'=>'parent'));?>';

  }

	
}
</script>
<?php 
$this->back_button= "<a href='".$this->html->url("/groupsview")."'><span>".__('Back')."</span></a>";
?>
<div class='row-fluid' style='width: 80%;'>
	<h3><?php echo __('Edit Contact Information')?></h3>
	<br/>
	<div class="span5" >
		<div class="box-small" >
			<h3 class="before-box-small"><?php echo __('Child information')?></h3>
			<div class="inner-div">
				<?php
				 echo $this->Form->create('GroupsUser',array('type'=>'file'));
						$role_type=$this->request->data['Member']['member_type'];
						echo $this->Form->input('Member.contact_id',array('type'=>'hidden','value'=>''));
						echo $this->Form->input('Member.request_status',array('type'=>'hidden','id'=>'request_status_id'));
						echo $this->Form->input('redirect',array('type'=>'hidden','id'=>'redirectid'));
						echo $this->element('Contacts.form', array('contact_type'=>'child-member'));
						$this->Form->unlockField('Contact.city_id');
						 echo  $this->Form->submit(__('Submit'),array('class'=>'btn green_gradient'));
					 echo $this->Form->end();
				?>
			</div>
		</div>
	</div>
	<div id = 'parents-info' class="span5" >
			<?php
			if (count($parents)==1)
				$first_parent=$parents['Parent'];
			else 
				$first_parent=$parents[0]['Parent'];

				echo '<div class="box-small"><h3 class="before-box-small">'.__('First Parent').'<span class="action-in-title"><a href="'.$this->html->url(array('plugin'=>'contacts','controller'=>'contacts','action'=>'edit',$first_parent['id'],'parent')).'"><i class="icon-pencil"></i>'.__('Edit').'</a></span></h3>';
				echo '<div class="inner-div">';
				echo $this->element('Contacts.display',array('contact'=>$first_parent));
				echo '</div></div>';
				
			if (count($parents)==2){
				echo '<br/><div class="box-small"><h3 class="before-box-small">'.__('Second Parent').'<span class="action-in-title"><a href="'.$this->html->url(array('plugin'=>'contacts','controller'=>'contacts','action'=>'edit',$parents[1]['Parent']['id'],'parent')).'"><i class="icon-pencil"></i>'.__('Edit').'</a></span></h3>';
				echo '<div class="inner-div">';
				echo $this->element('Contacts.display',array('contact'=>$parents[1]['Parent']));
				echo '</div></div>';
			}
			else 
			echo 	'<h3 class="before-box-small" style="width:60%;margin:10px auto;"><i class="icon-plus"></i>'.
				$this->html->link(__('Add Second Parent'),array('plugin'=>'contacts','controller'=>'contactsrelations','action'=>'add',$this->request->data['Contact']['id'],'second-parent')).'</h3>'.
	'</div>';
	?>
</div>
