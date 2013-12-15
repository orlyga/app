<div id="adult_mem_tables" >
			<table  class="table table-bordered table-striped table-hover">
				<thead>
				<tr>
						<th><?php echo __('Name');?></th>
						<th><?php echo __('phone');?></th>
						<th><?php echo __('email');?></th>
						<th class="actions"><?php echo __('Actions');?></th>
				</tr>
				</thead>
			<?php
			$i = 0;
			$edit_member=false;	
			$delete_member=false;			
			if($role_id>0){
				if($this->Acl->linkIsAllowedByRoleId($role_id, array('plugin'=>'Groups','controller'=>'members','action'=>"edit_adult_member")))
				$edit_member=true;	
				if($this->Acl->linkIsAllowedByRoleId($role_id, array('plugin'=>'Groups','controller'=>'groupsusers','action'=>"delete_member")))
				$delete_member=true;
			}
			foreach ($members as $member):
				$cur_user=false;
				$class = null;
				if ($member['Contact']['id']==$contact_id) $cur_user=true;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
					?>
					<tbody>
					<tr id="<?php echo $member['Contact']['name'].' '.$member['Contact']['last']?>" class="<?php echo $member['Member']['member_type']?>">
								<td>
									<?php echo $member['Contact']['name'] . " ". $member['Contact']['last']?>
								</td>
								<td>
									<i class="icon-mobile-phone"></i><a class='phone' href="tel:<?php echo $member['Contact']['cellphone']?>"><?php echo $member['Contact']['cellphone'].'</a>'; if (isset($member['Contact']['phone'])) echo  '<br/><i class="icon-phone"></i><a class="phone" href="tel:'.$member['Contact']['cellphone'].'">'.$member['Contact']['phone']?></a>
								</td>
								<td>
									<i class="icon-envelope"></i><a href="mailto:<?php echo $member['Contact']['email'] ?>"><?php echo $member['Contact']['email'] ?></a>
								</td>
								<td class="actions">
									<?php
					
									if ($cur_user|| $edit_member)
										 echo '<i class="icon-pencil"></i>'. $this->Html->link(__('Edit', true), array('plugin'=>'groups','controller'=>'members','action' => 'edit_adult_member', $member['Member']['id'])); 
									if ($cur_user|| $delete_member)	
										 echo '<i class="icon-trash"></i>'.$this->CroogoHtml->link(__('Delete', true), array('plugin'=>'groups','controller'=>'members','action' => 'delete', $member['Member']['id']), null, sprintf(__('Are you sure you want to delete %s?'),$member['Contact']['name']." ".$member['Contact']['last'] )); 
									?>					
								</td>
					</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
</div>	



