<link rel="stylesheet" href="<?php echo $this->webroot . 'css/'; ?>/jquery-ui.css" type="text/css" media="screen" />
 
 <script type="text/javascript">

 function next_tab(){
 	var i=$('#tabs').tabs('option', 'selected');
 	$("#tabs").tabs("enable",i+1);
	$("#tabs").tabs("select",i+1);
 }
 $(function() {
               $("#tabs").tabs({selected:1});
               $('#tabs').tabs("option","disabled",[1]); 
               
           });
	

 function updateUserId(data) {
 		 $('#sending').fadeOut();
		if($(data).find("#statusUser").val()=="error"){
		 	$("#replaceFields").html(data);
		 	
	 	}
	else {
		$("#GroupUserId").val($(data).find("#UserId").val());
		$("#ContactName").val($(data).find("#UserName").val());
		$("#ContactEmail").val($(data).find("#UserEmail").val());
		next_tab();
		
		
	}
 }
 function updateGroup(data) {
 		 $('#sending').fadeOut();
		if($(data).find("#request_status_id").val()=="error"){
		 	$("#replaceFieldsgroup").html(data);
		 	}
	else {
		next_tab();
			
	}
}
 function updateContactId(data) {
 		 $('#sending').fadeOut();
		if($(data).find("#request_status_id").val()=="error"){
			var select_html = $("#combobox").html();
		 	$("#replaceFieldcontact").html(data);
		 	$("#combobox").html(select_html);
		 	$(data).find("#request_status_id").val();
		 	$("#combobox").val($(data).find("#ContactCity").val());
		 	$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	 	}
	else {
		$("#GroupContactId").val($(data).find("#ContactId").val());
		$("#GroupName").val($(data).find("#ContactName").val());
		$("#GroupAddForm").find(":submit").click();

	}

}
	</script>

<div id="tabs" class="ui-tabs-nav">

	<ul>
		<li><a href="#tabs-1"><?php echo __('Member Information',true) ?></a></li>
		<li><a href="#tabs-2"><?php echo  __('Family Information',true) ?></a></li>
	</ul>
	<div id="tabs-1">
<?php  // need to go back and enable security orly !!! echo $this->requestAction(array('controller' => 'users', 'action' => 'add'),array('named'=>array('registration'=>'head')));
   ?>
			   <div class="families form">
			<?php echo $this->Form->create('Family');?>
				<fieldset>
				<?php
				//orly: take group from session
					echo $this->Form->input('group_id',array('type'=>'hidden','value'=>'51'));
					echo $this->Form->input('term_id',array('type'=>'hidden','value'=>'1085'));
				?>
				</fieldset>
			<?php echo $this->Form->end(__('Submit', true),array('type'=>'hidden'));?>
			</div>
	    <div id="replaceFieldsmember0">
			<?php  echo $this->requestAction(array('controller' => 'members', 'action' => 'familymember'),array('named' => array('role' => '1086','index'=>'0')));?>
	 	</div>
		<div id="sending" style = "display:none"><?php echo __("Checking",true)?></div>
	</div>
	<div id="tabs-2">
		 <div id="replaceFieldsmember1">
   			<?php  echo $this->requestAction(array('controller' => 'members', 'action' => 'familymember'),array('named' => array('role' => '1087','index'=>'1')));?>
 		</div>
 		 <div id="replaceFieldsmember2">
   			<?php  echo $this->requestAction(array('controller' => 'members', 'action' => 'familymember'),array('named' => array('role' => '1088','index'=>'2')));?>
 		</div>
	 </div>
	
</div>








