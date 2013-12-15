 <?php echo $this->Html->script('autocomplete');
if ($mode==""){
 		$this->title_for_layout=__("Create New Group",true);
		$head = "head";
		$tab_no = ($user_exist)? 2 :0;
		$title_welcome="";
		$mode_login="new_group";
		$new_group="";
 	}
 	else {
 		$this->title_for_layout=__("New Member",true);
		$head="";
		$tab_no=(in_array($mode,array("add_logged_member","add_unlogged_member")))? 3 :0;
		$title_welcome=	'<h2>'.  __('Welcome to Group')." ".$group_name.'</h2>';
$mode_login="add_member";
$new_group=SessionComponent::read('Group.Dest.Id');
 			
 	}
 	
	$this->content_sidebar = $this->Html->image("/img/registration.png",array("alt"=>__('Create Contact List',true)));
 global $tiny;
 $tiny= $this->Tinymce->input('message', array( 
		
            'label' => 'Content' ,
           
            ),array( 
                'language'=>'he' ,
                'directionality' => "rtl",
                 'width'=>'600px',
                
            ), 
            'basic' );
 ?>

 <script type="text/javascript">
 var mode="<?php echo $mode?>";
 $("body").delegate("#submit_login","click", function (event) {
 		$.ajax({
 			beforeSend:function (XMLHttpRequest) { $('#sending').fadeIn();var $this=$(this);}, 
 			data:$("#submit_login").
 			closest("form").serialize(), 
 			success:function (data, textStatus) {
 				updateUserId(data,"<?php echo $mode_login?>");}, type:"post", 
 				url:"\/kshurim\/users\/login\/<?php echo $mode_login?>"});
return false;});
$("body").delegate("#submit_add_user","click", function (event) {
 		$.ajax({
 			beforeSend:function (XMLHttpRequest) {var $this=$(this);}, 
 			data:$("#submit_add_user").
 			closest("form").serialize(), 
 			success:function (data, textStatus) {
 				updateUserId(data,"<?php echo $mode_login?>");}, type:"post", 
 				url:"\/kshurim\/users\/add\/add_user"});
return false;});
 	$("body").delegate("#submit_add_member","click", function (event) {
 		$.ajax({
 			beforeSend:function (XMLHttpRequest) {var $this=$(this);}, 
 			data:$("#submit_add_member").
 			closest("form").serialize(), 
 			success:function (data, textStatus) {
 				updateMember(data);}, type:"post", 
 				url:"\/kshurim\/members\/add\/"});
return false;});
$("body").delegate("#submit_add_family","click", function (event) {
 		$.ajax({
 			beforeSend:function (XMLHttpRequest) {var $this=$(this);}, 
 			data:$("#submit_add_family").
 			closest("form").serialize(), 
 			success:function (data, textStatus) {
 				updateFamily(data);}, type:"post", 
 				url:"\/kshurim\/contactsrelations\/add\/"});
return false;}); 
 
$(function() {

 	 $( "#accordion" ).accordion({
            heightStyle:"auto",
            event: "mouseup",
            active:false,
            icons:false,
           
        });
               $("#tabs").tabs({selected:<?php  echo $tab_no?>});
              // $('#tabs').tabs("option","disabled",[1]); 
               
           });
	
function setMemberid(contact_id){
	alert("need to remove alert line 77");
	$("#GroupsUserMemberContactId").val(contact_id);
	$("#GUForm input[type='submit']").click();
	
}
function group_saved(data){
	var error;
	data = '<div>'+data+'</div>';
	if($(data).find("#request_status_id").val()=="error"){
		error=$(data).find(".error-message").html();
	}
	var dest=$(data).find("#tab_dest").html();
	if ((error == null) || (error =="")|| (error ==undefined))
	 	error=$(data).find(".error-message").html();
	if ((error != null) && (error !="")) {
		$("#tabs-member .error-message").html(error);
		$("#tabs").tabs("select","tabs-member");
		return false;
	}
	
	var group_id=$(data).find("#group_id").html();
	
	if ((dest != null) && (dest !="")){
		if (dest="tabs-invite"){
				set_invite();
			}
		}
	else {
		//if ((group_id != null) && (group_id !="")) 
		//redirect_groupview("redirect_group",group_id);
	}
		
	
}
function set_invite(){
	$.ajax({
				success:function (data, textStatus) {$("#replaceInvite").html(data);
				$("#tabs").tabs("select","tabs-invite");}, 
				url:"\/kshurim\/groupsusers\/invite"});
	
}
function add_child_member(){
	$.ajax({
				success:function (data, textStatus) {$("#replace-member").html(data);
				$("#tabs").tabs("select","tabs-member");}, 
				url:"\/kshurim\/members\/add\/add-child-member"});
}
 function set_family(data){
 	$("#replaceFamily").html(data);
 }
 function updateMember(data) {
 		 $('#sending').fadeOut();
		if($(data).find("#request_status_id").val()=="error"){
		 	$("#replace-member").html(data);
		 	}
	if($(data).find("#request_status_id").val()=="success") {
		var contact_id=$(data).find("#MemberContactId").val();
		if(contact_id>0){
			$("#GUForm input[type='submit']").click();
			return;
		}
		var type=$(data).find("#MemberGrouprole").val();
		if(type=='child-member'){
			family_exist=$(data).find("#ContactContactsRelation0ContacId").val();
			if(family_exist>0) $("#GUForm input[type='submit']").click();
			else 
			{
				$.ajax({
				data:{type:this.value}, 
				success:function (data, textStatus) {set_family(data);}, 
				url:"\/kshurim\/contactsrelations\/add"});

				$("#tabs").tabs("select","tabs-momdad");
			}
		}
		else
		$("#GUForm input[type='submit']").click();
	}
}
function updateFamily(data) {
 		 $('#sending').fadeOut();
		if($(data).find("#request_status_id").val()=="error"){
		 	$("#replaceFamily").html(data);
		 	}
	else {	
	$("#replaceFamily").html(data);				
		$("#GUForm input[type='submit']").click();
	}
}
 function next_tab(){
 	var i=$('#tabs').tabs('option', 'selected');
 	$("#tabs").tabs("enable",i+1);
	$("#tabs").tabs("select",i+1);
 }
  function set_member(data){
  	var contact_id=$(data).find("#MemberContactId").val(); 
  	//found a match
 	var type=$(data).find("#MemberGrouproleId").val(); 
		$("#GroupsUserMemberGrouproleIdslug").val(type);
		if(contact_id>0){
  		$("#GUForm input[type='submit']").click();
  		return true;
  	}
		$("#replace-member").html(data);	
		$("#tabs").tabs("select","tabs-member");
}
 function redirect_groupview(data,gr_id){
 	if (data=="add_member"){
 		var newgroup="<?php echo $new_group?>";
 			alert(newgroup);
 		 		window.location = '<?php echo $this->Html->url('/registration/')?>'+newgroup;
 		 		return;
 	}
 	if(data=="redirect_group"){
 		var tab=$("#GroupsUserMemberGrouproleIdslug").val();
 			if ((tab.indexOf('staff')>0)||(tab=="staff")) tab="#staff"; else tab="";
 			window.location = '<?php 
 			 $dest=  (SessionComponent::read('Group.Dest.Id')==SessionComponent::read('Group.Id')) ? "/":"/groupsusers/switch_group/";
 			if ($dest=="/")
 			echo $this->Html->url($dest)."'+tab";
 			 else 
 			echo $this->Html->url($dest)."'+gr_id+tab";
 			?>
 	}
 }

 function updateUserId(data,mode_user) {
 	var error=$(data).find(".error-message").html();
	 if ((error != null) && (error !="")) {
	 	if(mode_user=="add_user")
	 		$("#replaceFieldsUser").html(data);
	 	else	
	 		$("#replaceFieldsLogin").html(data);
	 	return;
	 	}
 	if (mode_user=="redirect_group") 	{
 		redirect_groupview(mode,null);
 		return;
 	}

	 $('#sending').fadeOut();
	 
	
		$("#PrintUsername").val($(data).find("#PrintUsername").val());
		$("#PrintPaswrd").val($("#UserPassword").val());
		if (((mode_user=="add_user")&&(mode!="add_member"))||(mode_user=="new_group")){
			$("#tabs").tabs("enable","tabs-groupinfo");
			$("#tabs").tabs("select","tabs-groupinfo");
		}
		else {
			$("#tabs").tabs("enable","tabs-membertype");
			$("#tabs").tabs("select","tabs-membertype");
			}
	
	}

 function updateGroup(data) {
 		 $('#sending').fadeOut();
 		 var result=$(data).find("#request_status_id").val();
		if(result=="error"){
		 	$("#replaceFieldsgroup").html(data);
		 	}
		if(result=='tabs-invite'){
				set_invite();
		}
}
function updateFamilyContact(data,role) {
	if($(data).find("#ContactsRelation0ContactId").val()>0){
		 		 $('#sending').fadeOut();
				if($(data).find("#request_status_id").val()=="error"){
				 	$("#replaceFamilysmember").html(data);
				 	}
				else 	$("#GUForm input[type='submit']").click();
	}
	else 
	{ 
		if(role=='child'){
			set_momdad_data();
			$("#tabs").tabs("enable","tabs-momdad");
			$("#tabs").tabs("select","tabs-momdad");
		}
		else
			if (set_momdad(data,role)) $("#GUForm input[type='submit']").click();
	}
}
 function setMember(value){
 			var type=$("#selectmembertype").val();
}
 
	</script>
<?php
$this->Js->get('#tabs-membertype input')->event('click',
$this->Js->request(
		array('controller'=>'members','action' => 'add'),
		array( 
		'data'=>"{type:this.value}",
		'dataExpression' => true,
		'success'=>	'set_member(data);'	))
)?>
<div id="tabs" class="ui-tabs-nav">

	<ul>
		<li><a href="#tabs-register"><?php echo __('Registration',true) ?></a></li>
		<li><a href="#tabs-login"><?php echo __('Login',true) ?></a></li>
		<li><a href="#tabs-groupinfo"><?php echo  __('General Information',true) ?></a></li>
		<li><a href="#tabs-membertype"><?php echo __('Member type',true) ?></a></li>
		<li><a href="#tabs-selectmember"><?php echo __('Select Member',true) ?></a></li>
		<li><a href="#tabs-member"><?php echo __('Add Member',true) ?></a></li>
		<li><a href="#tabs-momdad"><?php echo __('Set Family',true) ?></a></li>
		<li><a href="#tabs-invite"><?php echo __('Invite',true) ?></a></li>


	</ul>
	<div id="tabs-register">
		 <div id="replaceFieldsUser">
		 	<?php echo $title_welcome?>
			<?php  echo $this->requestAction(array('controller' => 'users', 'action' => 'add',$head));
			   ?>
	    </div>
	</div>
	<div id="tabs-login">
		 <div id="replaceFieldsLogin">
			<?php echo $this->requestAction(array('controller' => 'users', 'action' => 'login',$mode_login));
			   ?>
	    </div>
		<div id="sending" style = "display:none"><?php echo __("Checking",true)?></div>
	</div>
	<div id="tabs-groupinfo">
	    <div id="replaceFieldsgroup">
			<?php  
			if ($mode=="")
			echo $this->requestAction(array('controller' => 'groups', 'action' => 'add'));?>
	 	</div>
	 </div>	
	 <div id="tabs-membertype">
	 	<?php 
	       			$options=array("child-member"=>__("Member in The Group"),
	       							"head-staff"=>__('Group Head Staff'),
	       							"staff"=>__('Staff'),
									);
					$attrbitubes=array('id'=>'selectmembertype','type'=>'radio','legend'=>__('I am:'),'separator'=>'<br/>','options' => $options,);
					echo $this->Form->input('member_type',$attrbitubes);
					?>
	       
	 </div>
	 <div id="tabs-selectmember">
	 				<?php  echo $this->requestAction(array('controller' => 'groups', 'action' => 'add_mem_instance',$mode));?>

	 	</div>
	<div id="tabs-member">
			<div id="replace-member">

			</div>
			<div class="error-message"></div>
		     <div id="replaceFamilysmember" style="display:none">
		 	 </div>  
	</div>
	
	<div id="tabs-momdad">
		     <div id="replaceFamily">
		 	 </div>  
	</div>
	<div id="tabs-invite">
	       <div id="replaceInvite">
	 		</div>
	</div>
</div>
<div style="display:none">
	<?php
echo $this->Js->link('saveAll', array('controller'=>'groups', 'action'=>'add','act'=>'saveAll',), array('id'=>'SaveAll','success'=>'javascript:reset_group()','error'=>'javascript:reset_group()'));
?>
	</div>
	<div >
	<?php
echo $this->Html->link('import', array('controller'=>'groupsusers', 'action'=>'importgroup'));
?>
<?php
echo $this->Html->link('invite',"#",array('onClick'=> "set_invite();return false;"));
?>

	</div>
	
<div id="sending" style = "display:none;position:absolute;top:400px;left:400px"><?php echo $this->Html->image("waiting-animation.gif")?></div>

