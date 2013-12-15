<?php
if ($group_id>0){
	//get the temp user of the group
}
//the user is the current user
else {
echo $this->croogoForm->input($parent.'.User.id',array('type'=>'hidden','value'=>$user_id));
//$this->Form->unlockField($parent.'.User.id');
}					
?>