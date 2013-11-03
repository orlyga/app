<!-- contacts/add.ctp -->
<script>
    function afterContactCheckExist(data){
        alert('<?php echo __("This contact is already in the system,Would you like to merge?") ?>')
    }
</script>
<div class="contact form">
	<h3><?php if($contact_type=='parent') echo __("Edit Parent Contact Information"); else echo __('Edit Contact information')?></h3>
<?php
 echo $this->Form->create('Contact',array('type'=>'file'));?>
	<?php	
		echo $this->Form->input('Contact.redirect',array('type'=>'hidden','id'=>'redirectid'));
	
echo $this->element('Contacts.form',array('contact_type'=>$contact_type,'parent'=>'Contact'));

$this->Form->unlockField('Contact.city_id');
	 		 echo  $this->Form->submit(__('Submit'),array('class'=>'btn green_gradient'));
			 echo $this->Form->end();

?>
</div>
