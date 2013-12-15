<script>
function after_set_member_type(){
	
	if (typeof set_member_type ==='function')
		set_member_type($('input[id*="MemberType"]').val());
}

</script>
<div id="tabs-membertype" class="button-list">
	<?php 
		//most likely that its a usual and not staff member, so lets set the member as default
		
	$value=(isset ($default))? $default :null;
	echo $this->CroogoForm->input($parent.'.member_type',array('type'=>'hidden','value'=>$value,)); 
	$show_types_list="";
	$label=(isset($by_group_admin)) ? __('Staff member') :__('I am a staff member');
	 ?>
	<ul id="membersTypesDiv" class='<?php echo $show_types_list; ?>'>
	 	<li ><a href="#" onClick="setinputfield('MemberType','child-member');after_set_member_type();" class="btn btn-block"><?php echo __("Member in The Group"); ?></a></li>
		<li><a href="#" onClick="setinputfield('MemberType','head-staff');after_set_member_type();" class="btn btn-block"><?php echo __('Group Head Staff'); ?></a></li>
		<li><a href="#" onClick="setinputfield('MemberType','staff');after_set_member_type();" class="btn btn-block"><?php echo __('Staff'); ?></a></li>
	</ul>
 </div>