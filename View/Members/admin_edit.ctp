<div class="members form">
<?php echo $this->Form->create('Member');?>
	<fieldset>
		<legend><?php __('Admin Edit Member'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('family_id');
		echo $this->Form->input('contact_id');
		echo $this->Form->input('term_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Member.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Member.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Members', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Families', true), array('controller' => 'families', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Family', true), array('controller' => 'families', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contacts', true), array('controller' => 'contacts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contact', true), array('controller' => 'contacts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Terms', true), array('controller' => 'terms', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Term', true), array('controller' => 'terms', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Message Relations', true), array('controller' => 'message_relations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Message Relation', true), array('controller' => 'message_relations', 'action' => 'add')); ?> </li>
	</ul>
</div>