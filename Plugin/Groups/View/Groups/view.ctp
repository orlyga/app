 <!--removed 14/10/2013 <script type="text/javascript" src="http://twitter.github.com/bootstrap/assets/js/bootstrap-dropdown.js"></script>-->

  <script type="text/javascript">

 $(function() {
 	<?php if (isset($modal)) { ?>
 	$('#myModal').modal(
 		{
 			show:true,
 			keyboard:false,
 			backdrop:'static'
 		}
 	);
 	<?php } ?>
 	var hash = window.location.hash.substring(1);
 	var tab;
switch(hash)
{
case "message":
  tab=3;
  break;
case "staff":
 tab=2;
  break;
case "contacts":
  tab=1;
  break;
default:
 tab=0;
}      

    $("a[href=#tabs-"+tab+"]").tab('show');
              // $('#tabs').tabs("option","disabled",[1]); 
               
           });
</script>

<?php
$group_vars=$this->Group->setVarsbyGroupType($group['Group']['term_id']);
$this->back_button= "<a href='".$this->html->url("/groupslist")."'><span>".__('Back')."</span></a>";

$this->menu_actions_addition="<li class='dropdown submenu'>
									 			<a href='".$this->Html->url("/printgroup")."'>
							  					<i class='icon-print'></i>". __('Print Contact List',true)."</a>
									 		</li>";
$is_admin=false;
if($this->Acl->linkIsAllowedByRoleId($role_id, array('plugin'=>'groups','controller'=>'groups','action'=>"edit"))){
$is_admin=true;
$x1=__("Admin Actions");
$this->menu_addition = <<<EOT
<li class="dropdown left_hr">
		 <a data-toggle="dropdown" class="dropdown-toggle" href="#">$x1</a>
		 <ul class="dropdown-menu">
			<li class="dropdown submenu"> {$this->Html->link(__('Edit Group'), array('plugin'=>'groups','controller'=>'groups','action'=>"edit",'group_id'=>$group['Group']['id']),array('class'=>''))} </li>
			<li class="dropdown submenu"> {$this->Html->link(__('Add Member'),'/addMember',array('class'=>''))} </li>
			<li class="dropdown submenu hide"> {$this->Html->link(__('Add Staff'),'/addStaff',array('class'=>''))} </li>
			<li class="dropdown submenu"> {$this->Html->link(__('Invite Members'),'/invitetogroup',array('class'=>''))} </li>
			
		</ul>
          </li>
EOT;

?>

<?php } 

?>
<div class='row-fluid '>
	<div class='pull-left-rtl span5'>
		 	<h3><?php echo $group['Group']['name'];?></h3>
	</div>
	<div id='general-info' class='pull-right-rtl span5' style="margin: 1% 5%">
		<table class="table no-marginpadding">
		<?php 
		
		if(!empty($group['Contact']['city_id'])){?>
		<tr><td>
			<b><?php echo __("Group Info").":" ?></b>
			</td>
			<td>
				<?php $fieldset=array('name'=>array('class'=>'hide'),
								'last'=>array('class'=>'hide'),'email'=>array('class'=>'hide'),

								);
				echo $this->Element('Contacts.display',array('contact'=>$group['Contact'],'fieldset'=>$fieldset),array('cache'=>array('config'=>'groups_view','key'=>'group_info'.$group['Group']['id'])));?>
		</td></tr>
		<?php }
			if (count($head_staff)){?>
		<tr><td>
			<b><?php
          if (!isset($user_id)) $user_id=0;
          if($is_admin || $head_staff[0]['GroupsUser']['user_id']==$user_id) $edit_head=''; else $edit_head='hide';
                echo __($group_vars['head_staff_type'])."<a class='".$edit_head."' href=".$this->html->url('/updateMember/'.$head_staff[0]['Member']['id'])."><i class='icon-pencil' 'title'=".__('Edit')." 'alt'=".__('Edit')."></i>:" ?></b>
    
			</td>
			<td>
				<?php $fieldset=array('city_id'=>array('class'=>'hide'),'contact_comments'=>array('class'=>'contact comments'),
								
								);
			echo $this->Element('Contacts.display',array('contact'=>$head_staff[0]['Contact'],'fieldset'=>$fieldset),array('cache'=>array('config'=>'groups_view','key'=>'group_headstaff'.$group['Group']['id'])));?>
		</td></tr>
		
		
			<?php
		}
		else {
			if (isset($group_vars['head_staff_type']) && $is_admin)
			echo $this->Html->link(__('Add Information of')." ".__($group_vars['head_staff_type']),"/addStaff/head-staff",array('class'=>'btn green_gradient'));
			}
		if (isset($group_admin[0]))	{?>
		
		<tr>
			<td>
			<b><?php echo __("Group Administrator") ?></b>
			</td>
			<td>
				<?php 
						$fieldset=array('address'=>array('class'=>'hide'),
									'city_id'=>array('class'=>'hide'),
									'phone'=>array('class'=>'hide'),
									
									);
						echo $this->Element('Contacts.display',array('contact'=>$group_admin[0]['Contact'],'fieldset'=>$fieldset),array('cache'=>array('config'=>'groups_view','key'=>'group_admin'.$group['Group']['id'])));?>			</td>
		</tr>
		<?php }?>
	</table>
	</div>	

</div>
<div id="top-area" class="container_12" style="height:1000px">
	
	
	<div id="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tabs-1" data-toggle="tab"><?php echo __('Members',true) ?></a></li>
			<li class="hide"><a href="#tabs-2" data-toggle="tab"><?php echo  __('Staff',true) ?></a></li>
		 <?php 	//if($this->AclLink->checkbyrole("Conversations/messageslist"))
		 if (true)
 {?>

			<li class="hide"><a href="#tabs-3" data-toggle="tab"><?php echo __('Messages',true) ?></a></li>
		<?php }?>
	
		</ul>
		 <div class="tab-content box ">
			<div class="tab-pane  active" id="tabs-1">
					<?php echo $this->element("Groups.group_members_child_list",array("members"=>$child_members),array('cache'=>array('config'=>'groups_view','key'=>'mem_list1_'.$role_id.'_'.$group['Group']['id'])));?>			</div>
					<?php  // echo $this->element("Groups.group_members_child_list",array("members"=>$child_members));?>			</div>
<div class="tab-pane box simple-box" id="tabs-2">
								<?php echo $this->element("Groups.group_members_list",array("members"=>$staff_members),array('cache'=>array('config'=>'groups_view','key'=>'mem_list2_'.$role_id.'_'.$group['Group']['id'])));?>		</div>
        						<?php //echo $this->element("Groups.group_members_list",array("members"=>$staff_members));?>		</div>

		<div class="tab-pane box simple-box" id="tabs-3">
				<?php // echo $this->requestAction(array('controller' => 'conversations', 'action' => 'messageslist'));?>
		</div>
	</div>
</div>
	<div class="clear">	</div>
<?php if(isset($modal)) { ?>
	<div class="modal fade " id="myModal">
		
		<?php	
		
		$hide=($user_id>0)?'hide':"";?>
		<div id='modal-login' class='row-fluid'>
			  <div class="modal-header ">
			  	<h3 style="font-size:100%;font-weight:bold;border:0;padding:0"><?php echo __('Adding')." ".$name." ".__('To Contact List of')." ".$group_name;?></h3>
			  </div>
			  <div class="modal-body <?php if($user_id>0) echo "hide";?>">
			  	 <h5><?php echo __('Login to B-member.com') ?></h5>
			  	<?php echo $this->element('Users.login_form',array('show_registration_button'=>true)); ?>
			  </div>
		</div>
		<div id='modal-tab2' class=' <?php	echo ($hide=='')?'hide':''; ?>'>
			  
			  <div id='approval-div' class="modal-body <?php if(!$user_id>0) echo "hide";?>">
			  	
			  	
			    <a href="<?php echo $this->Html->url('/approveMember/');?>" class="btn green_gradient"><?php echo __('I would like to be a member in this group') ?></a>
			  	 <a class="" href="<?php echo $this->Html->url('/');?>" class="btn green_gradient"><?php echo __('No thanks') ?></a>

			  <?php // echo $this->Form->input('Contact', array('label' => __d('croogo', 'Accept Terms of use'),	'type' => 'checkbox','default' => false,));?>
			  </div>
		</div>
	</div>
<?php } ?>

