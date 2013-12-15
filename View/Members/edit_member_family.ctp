 <script type="text/javascript">

 function next_tab(){
 	var i=$('#tabs').tabs('option', 'selected');
 	$("#tabs").tabs("enable",i+1);
	$("#tabs").tabs("select",i+1);
 }
 function updateMember(data) {
 		 $('#sending').fadeOut();
		$("#addchild").html(data);
	if($(data).find("#request_status_id").val()=="success"){
	 x=$(data).find("#ContactLast").val();
		$("input[name='data[Contact][last]']").each(function(i){$(this).val(x)});
	 x=$(data).find("#ContactAddress").val();
			$("textarea[name='data[Contact][address]']").each(function(i){$(this).val(x)});
	x=$(data).find("#ContactCityId").val();
			$("select[name='data[Contact][city_id]']").each(function(i){$(this).val(x)});
	 x=$(data).find("#ContactPhone").val();
	 		$("input[name='data[Contact][phone]']").each(function(i){$(this).val(x)});
	 x=$(data).find("#ContactCellphone").val();
			$("#firstparent input[name='data[Contact][cellphone]']").val(x);
	 x=$(data).find("#ContactEmail").val();
		$("#firstparent input[name='data[Contact][email]']").val(x);
	next_tab();
			
	}
}
 $(function() {
               $("#tabs").tabs({selected:0});
              // $('#tabs').tabs("option","disabled",[1]); 
               
           });
	</script>

<div class="nicebox wclose">
	<header>
		<h3><?php echo __('Update your contact information')?></h3>
			<?php echo $this->Html->link('X',array('controller'=>'groups','action'=>'view'),array('class'=>'btn'))
?>
	</header>
<div id="tabs" class="ui-tabs-nav">

	<ul>
		<li><a href="#tabs-1"><?php echo  __('Member Information',true) ?></a></li>
		<li><a href="#tabs-2"><?php echo __('Family Information',true) ?></a></li>
		<li><a href="#tabs-3"><?php echo __('Change Password',true) ?></a></li>

	</ul>
	
	<div id="tabs-1">
	 	<div id="replaceFieldsMainMemeber">
	 		<?php
if (isset($this->params['named']['role_type'])) $role_type=$this->params['named']['role_type'];
	else if(!isset($role_type)) $role_type ='member';
 echo $this->Form->create('Member',array('type'=>'file','id'=>'addmember'.$role_type));?>
 	<fieldset>
	<?php  echo $this->element('members/form',array('role_type'=>$role_type));   
	echo $this->Form->input('Member.request_status',array('type'=>'hidden','id'=>'request_status_id'));
   $submit=__("Send");
	$submit_show="";
	$render= 'add';
	 echo $this->Form->input('Family.group_id',array('type' => 'hidden'));
	$contact_fields=array(
		'address'=>array('rows'=>2,'cols'=>17),
		'image'=>array('type'=>'display'));
		$submit=__("Parents");
		$submit_show='style="display:none"';
		
echo $this->element('contacts/form', array('fieldset'=>$contact_fields));
  
	 echo  $this->Js->submit($submit,
	  array( 'url'=> array('controller'=>'members',
	   'action'=>'edit','render'=>'edit_member_family'),
	   'before'=>'var $this=$(this);',
	    'class' => 'ajax-link button-elegant',
	    'buffer' => false,
	    'success'=>'updateMember(data);',
	    ));
	  
echo  '</fieldset>';
 echo $this->Form->end();?>
   
		</div>
   	</div>
   <div id="tabs-2">
	 	<div id="replaceFieldsParents" >
	 		
<?php  
//echo $this->requestAction(array('controller' => 'families', 'action' => 'edit'),array($this->data['Member']['family_id']));

echo $this->requestAction(array("controller"=>'families','action'=>'edit',$this->data['Member']['family_id']));?>
		</div>
	</div>
	<div id="tabs-3">
	 	<div id="replaceFieldsUser">
			<?php // echo $this->requestAction(array('controller' => 'users', 'action' => 'reset'),array('named'=>array('registration'=>'head')));   ?>
		</div>
	</div>
</div>

	