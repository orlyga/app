<div class="members index">
	
	<h2><?php __('Members');?></h2>
	<?php // echo $this->requestAction(array('controller' => 'members', 'action' => 'add'),array('named'=>array('registration'=>'head')));
?>
	<table class="nice minimal" cellpadding="0" cellspacing="0">
	
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
	<tr class="<?php echo $member['Terms']['slug']?>">
		
		<td>
			<?php echo $member['Contact']['name'] ."	". $member['Contact']['last'] ?>
		</td>
		
		<td>
			<?php echo $member['Contact']['phone'] ?>
		</td>
		<td>
			<?php echo $member['Contact']['email'] ?>
		</td>
		<td>
			<?php echo $member['Contact']['cellphone'] ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
	</table>
	
</div>
