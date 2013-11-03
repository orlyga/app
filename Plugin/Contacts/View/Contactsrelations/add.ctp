<?php echo $this->Html->script('jquery.validation');?>
<script>
$(function(){ 
$("#addContactRelations").submit(function(ev){
	if(!$("#addContactRelations").validation()){
		ev.preventDefault();
		return false;
		}
	});

});
	</script>

<div>
		<h3><?php echo __("Add Parent");?></h3>
		<div class="form" style="padding-top:2%">
		<?php echo $this->Form->create('ContactsRelation',array('id'=>'addContactRelations','type'=>'file'));

		echo $this->Form->input('ContactsRelation.relation_type',array('type'=>'hidden'));
		echo $this->Form->input('ContactsRelation.contact_id',array('type'=>'hidden'));
		echo $this->Form->input('redirect',array('type'=>'hidden'));
				
		echo $this->element("Contacts.form",array('contact_type'=>$contact_type));
		 echo  $this->Form->submit(__('Submit'),array('class'=>'btn green_gradient'));
					 echo $this->Form->end();
		?>

		</div>
	</div>
 