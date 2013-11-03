

<div id="mem_tables" >
	<table  class="nice"  cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo __('Name');?></th>
			<th><?php echo __('last');?></th>
			<th><?php echo __('image');?></th>
			<th><?php echo __('phone');?></th>
			<th><?php echo __('email');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	</thead>
	<?php
	$i = 0;
	foreach ($members as $member):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tbody>
	<tr id="<?php echo $member['Contact']['name'].' '.$member['Contact']['last']?>" class="<?php echo $member['Term']['slug']?>">
		
		
		<td>
			<?php echo $member['Contact']['name'] ?>
		</td>
		<td>
			<?php echo $member['Contact']['last'] ?>
		</td>
		
		<td>
			<?php if ($member['Contact']['image'] <> null) echo $this->Html->image($member['Contact']['image'],array('width'=>60)) ?>
			</td>
		<td>
			<?php echo $member['Contact']['phone'] ?>
		</td>
		<td>
			<?php echo $member['Contact']['email'] ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $member['Member']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $member['Member']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $member['Member']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
	</table>
</div>	
	<?php		 $this->Paginator->options(array('update' => '#childmember',  
												'evalScripts' => true,));?>
				<div style="text-align: center;">
	  		<?php 
	  		if ($this->Paginator->hasPage(2)) {
	  			echo $this->Paginator->prev();
	  			echo (" | ");
	  		} ?> 
	  		<?php echo $this->Paginator->numbers(); ?> 
	  		<?php 
	  		if ($this->Paginator->hasPage(2)) { 
	  			echo (" | ");
	  			echo $this->Paginator->next();
	  		} ?>
	  		
	  		<?php echo $this->Js->writeBuffer(); ?>
	  		</div>

	
</div>


