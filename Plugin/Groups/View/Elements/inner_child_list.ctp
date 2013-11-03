<script>
function resend_invite($member_id){
	$.ajax({
		url:"/resendInvite/"+$member_id,
		type : 'GET',
		 success : function(data){
                  //parent page will handle results 
                        //handle partial match - notify user about mismatch
                         alert(data);
                     
		        },
		        error : function(XMLHttpRequest, textStatus, errorThrown) {
		            //$('#login_error').show();
		            //ajax_failed(errorThrown);
		            alert("error :: " + textStatus + " : " + errorThrown);
		        }
		    });
}
</script>
	<table  class="table table-bordered table-striped table-hover span5" style="">
		
	<thead >
	<tr>
			<th></th>
			<th><?php echo __('Name');?></th>
			<th><?php echo __('Details');?></th>
			<th class="actions"></th>
	</tr>
	</thead>
	
	<?php
	$i=isset($local_count) ? $local_count:0;
$edit_member=false;	
$delete_member=false;			
if($role_id>0){
	if($this->Acl->linkIsAllowedByRoleId($role_id, array('plugin'=>'Groups','controller'=>'members','action'=>"edit_child_member")))
	$edit_member=true;	
	if($this->Acl->linkIsAllowedByRoleId($role_id, array('plugin'=>'Groups','controller'=>'groupsusers','action'=>"delete_member")))
	$delete_member=true;	
}
$fieldset=array('address'=>array('class'=>'hide'),
				'city_id'=>array('class'=>'hide'),
				'gender'=>array('class'=>'hide'),
				'birth_date'=>array('class'=>'hide'),
				'image'=>array('class'=>'hide'),
				'phone'=>array('class'=>'hide'));
$fieldset_child=array('name'=>array('class'=>'hide'),
					'last'=>array('class'=>'hide'),
					'email'=>array('class'=>'hide'),
					'gender'=>array('class'=>'hide'),
					'birth_date'=>array('class'=>'hide'),
					'cellphone'=>array('class'=>'hide')	);
	$cur_member_id=0;
	$duplicate=false;
	if(!isset($user_id)) $user_id=0;
	foreach ($members as $key=>$member):
		$cur_user=false;
		$class = null;
		if ($member['GroupsUser']['user_id']==$user_id) $cur_user=true;
		if($member['Member']['id']==$cur_member_id) $duplicate=true; else {
		$cur_member_id=$member['Member']['id'];
		
		
		}
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
	?>
	<?php if($duplicate) continue; ?>
	<tbody>
	<tr id="tr_<?php echo $i ?>" class="<?php echo $class." " . $member['Member']['member_type']?>">	<td class='td_img_class '>
			<?php if ($member['Contact']['image'] <> null) echo $this->Html->image($member['Contact']['image'],array()) ?>
			</td>
		<!--Name-->
		<td class="child_name" ><div class="mem_tab name"><?php echo "<span>";
			if($member['Contact']['gender']>1) echo "<i class='icon-female medium'></i>";
			if($member['Contact']['gender']==1) echo "<i class='icon-male medium'></i>";
			echo "</span>"; ?>
			<?php echo $member['Contact']['name']." ". $member['Contact']['last'] ?></div>
			<?php if(isset($member['Contact']['birth_date'] )){
							$member['Contact']['birth_date']= date('d.m.Y', strtotime($member['Contact']['birth_date']));
							echo '<span class="birth_date_list" style="font-size:80%">';
							echo $this->html->image('birthday-icon.png'); 
							echo $member['Contact']['birth_date'].'</span>';}?>
			<p>
			<?php 
		echo $this->element("Contacts.display",array('contact'=>$member['Contact'],'fieldset'=>$fieldset_child))?>

			</p>
		</td>
		<!--Details-->
		<td class="family-details" >
		
				<?php
		foreach ($member['Contact']['ContactsRelation'] as $family_mem):
					if ($family_mem['id']==$contact_id) $cur_user=true;
					?>
					<div class="family_member btn">
						<?php echo $this->element("Contacts.display",array('contact'=>$family_mem,'fieldset'=>$fieldset))?>
					</div>
			<?php endforeach; ?>		
		<?php if (!isset($members[$key+1]) ||$member['Member']['id']<> $members[$key+1]['Member']['id']){ ?>
				</td>
		<td class="actions">
			<?php
			
			if ($cur_user|| $edit_member)
				 echo '<i class="icon-pencil"></i>'. $this->Html->link(__('Edit', true), array('plugin'=>'groups','controller'=>'members','action' => 'edit_child_member', $member['Member']['id'])); 
			if ($edit_member)	
				 echo "<a href='#' onClick='resend_invite({$member['Member']['id']})'><i class='icon-envelope' alt='".__('Resend Invite')."'></i></a><br/>"; 
			if ($cur_user|| $delete_member)	
				 echo '<i class="icon-trash"></i>'.$this->CroogoHtml->link(__('Delete', true), array('plugin'=>'groups','controller'=>'members','action' => 'delete', $member['Member']['id']), null, sprintf(__('Are you sure you want to delete %s?'),$member['Contact']['name']." ".$member['Contact']['last'] )); 

			?>
			
		</td>
	</tr>
	<?php } ?>
<?php endforeach; ?>
</tbody>
	</table>
	
	



