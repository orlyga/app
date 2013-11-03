<div class="groups form">

<?php echo $this->Form->create('Group',array('type' => 'file'));?>
	<fieldset>
		<legend><?php echo __('Edit Group'); ?></legend>
	<?php
			echo $this->Form->input('doc', array('label' => __('Upload'), 'type' => 'file',));
			echo $this->Form->input('group_no',array('value'=>'10'));
			echo $this->Form->input('usenm',array('value'=>'qq@xx.com'));
			echo $this->Form->unlockField('username');
			echo $this->Form->unlockField('group_id');
			
			
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

