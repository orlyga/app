<?php
if (isset($contacts)){
if(count($contacts)==1) {$contacts[0]['Contact']=$contacts['Contact'];
							unset($contacts['Contact']);}
	 	foreach ($contacts as $contact) {
			$value=$contact['Contact']['name']."  ".$contact['Contact']['last'];
			$options[$contact['Contact']['id']]= $value;
			
		}
}

 echo $this->Form->create('Member');
echo $this->Form->input('Member.request_status',array('type'=>'hidden'));
echo $this->Form->input('Member.grouprole_id',array('type'=>'hidden'));
echo $this->Form->input('Member.grouprole',array('type'=>'hidden'));
$attrbitubes=array('type'=>'radio','legend'=>__('Who will be joining the Group'),'selected'=>'none','separator'=>'<br/>','options' => $options,);
echo $this->Form->input('MemberContactId',$attrbitubes);
$this->Form->unlockField("Member.request_status");
$this->Form->unlockField("Member.grouprole_id");
$this->Form->unlockField("Member.grouprole");

 echo $this->Form->end();
if($role_type=="child-member")
	echo $this->Html->link(__('Add Child to the Family'), 'javascript:add_child_member()',array('class'=>'button-elegant-long')); 

	?>	