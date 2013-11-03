<?php  
if (isset($error)) echo '<div class="error-message">'.$error.'</div>';
if (isset($returntotab)) echo '<div id="tab_dest" style="dispaly:none">'.$returntotab.'</div>';
	 	
		
	echo '<div id="GUForm">';
		 echo $this->Form->create('GroupsUser');
		 echo $this->Form->input('GroupsUser.Member.contact_id',array('type'=>'hidden'));
		 echo $this->Form->input('GroupsUser.Member.grouprole',array('type'=>'hidden'));
		 $this->Form->unlockField('GroupsUser.Member.grouprole');
		 $this->Form->unlockField('GroupsUser.Member.contact_id');
		 echo $this->Form->input('GroupsUser.request_status',array('type'=>'hidden'));
		 echo  $this->Js->submit(__('Continue',true),
	  array( 'url'=> array('controller'=>'groups',
	   'action'=>'add_mem_instance'),
	    'class' => 'hide',
	    'buffer' => false,
	    'success'=>'group_saved(data);'
	    ));
	 
 echo $this->Form->end();
 echo '</div">';
		$options=$arr_child;
		$attrbitubes=array('type'=>'radio','legend'=>__('Who belongs to the Group'),'selected'=>'none','separator'=>'<br/>','options' => $options,'onClick'=>'setMemberid(this.value);');
		echo '<div id="select_child" style="display:none">';
				echo $this->Form->input('selectchild',$attrbitubes);
							echo $this->Html->link(__('Add Child to the Family'), 'javascript:add_family_member()',array('class'=>'button-elegant-long')); 
					
					echo '</div>';
		$options=$arr_parents;
		$attrbitubes=array('type'=>'radio','legend'=>__('Who belongs to the Group'),'selected'=>'none','separator'=>'<br/>','options' => $options,'onClick'=>'setMemberid(this.value)');
		echo '<div id="select_parent" style="display:none">';
					echo $this->Form->input('selectcontact',$attrbitubes);
		echo '</div>';
echo '</div>';
					
	 ?>