<?php 
if (!isset($is_father)) $is_father=0;

?>
<script>
function sec_parent_set(){
 	var x=$("#ContactsRelationSecond").val();
 	if (x=='0'){
 		$("#ContactsRelationSecond").val('1');
 		$("#onoff").removeClass('off_button').addClass('on_button');
		$("#sec_fields").css("right","20px"); 
		$("#second-parent").css("height",$("#first-parent").css("height"));		
 		
 	}
 	else{
 		$("#ContactsRelationSecond").val('0');
 		$("#onoff").removeClass('on_button').addClass('off_button');
	   $("#sec_fields").css("right","-3000px");
	   	$("#second-parent").css("height","100px");		
 		
 	}
 }
$(function() {
	$("#second-parent").css("height",$("#first-parent").css("height"));
	<?php if(isset($is_father)&&($is_father==0)){?>
		$("#onoff").removeClass('on_button').addClass('off_button');
		$("#sec_fields").css("right","-3000px");
	   	$("#second-parent").css("height","100px");
	   	<?php }?>
	
});
</script>
<?php
if (isset($this->params['named']['index'])) $index=$this->params['named']['index']; else $index="";
$email=false;
$hostingdiv="";
$submit_class="button-elegant";

 echo $this->Form->create('ContactsRelation');?>
<div id='first-parent' class='grid_4 nicebox'>
	
		<div class="title" ><?php echo __('First Parent')?></div>
				<div class="error-message"><?php if(isset($error_first)) echo $error_first?></div>

 	<?php		echo $this->Form->input('id',array('type'=>'hidden'));
 				echo $this->Form->input('ContactsRelation.second',array('type'=>'hidden','value'=>'1'));
			   	echo $this->Form->input('ContactsRelation.request_status',array('type'=>'hidden','id'=>'request_status_id'));
 				$contact_fields=array(
			    'name'=>array('div'=>array('class'=>'input text noinline')),
				'image'=>array('show'=>'show'),
				'cellphone'=>array('type'=>'display'),
				'address'=>array('rows'=>2,'cols'=>17),
				'gender'=>array('type'=>'hidden'),
				'phone'=>array('type'=>'hidden'),
				'birthdate'=>array('type'=>'hidden'),
				)	;
				echo $this->element('contacts/form', array('parent'=>'ContactsRelation','parent_index'=>0,'fieldset'=>$contact_fields,'hostingdiv'=>'first-parent'));
	 			echo $this->Form->input('ContactsRelation.0.relation',array('type'=>'hidden','value'=>'first-parent'));
				echo $this->Form->input('ContactsRelation.0.relation_id',array('type'=>'hidden',));
				
	?>

	</div>
	
<div  class="grid_1"></div>
<div id='second-parent' class="grid_4 nicebox" style="position:relative">
	<div class="title" ><?php echo __('Second Parent')?></div>
	<div id="onoff" class="on_button" ><a href="javascript:sec_parent_set()" ><?php echo $this->Html->image('blank.png',array('width'=>'40px','height'=>'80px','alt'=>__('open/close')))?></a></div>
	<div id="sec_fields" style="position:absolute;top:40px;right:20px">
		<div class="error-message"><?php if(isset($error_second)) echo $error_second?></div>
	<?php
		
		echo $this->element('contacts/form', array('parent'=>'ContactsRelation','parent_index'=>1,'fieldset'=>$contact_fields,'hostingdiv'=>'second-parent'));
		echo $this->Form->input('ContactsRelation.1.relation',array('type'=>'hidden','value'=>'second-parent'));
		echo $this->Form->input('ContactsRelation.1.relation_id',array('type'=>'hidden',));
				
	?>
	</div>
</div>
 <div class='clear'></div>
 <div class="grid_10">
 <?php
			if($index<>"") $field='Contact.'.$index; else $field='Contact';
			$this->Form->unlockField('ContactsRelation.request_status');
			$this->Form->unlockField('ContactsRelation.id');
			$this->Form->unlockField('ContactsRelation.second');
			$this->Form->unlockField('ContactsRelation.0.relation');
			$this->Form->unlockField('ContactsRelation.0.relation_id');
			$this->Form->unlockField('ContactsRelation.1.relation');
			$this->Form->unlockField('ContactsRelation.1.relation_id');
			echo $this->Form->end(array('class' => 'ajax-link button-elegant','label' => __('Next'),'id'=>'submit_add_family',));
			
			/*echo  $this->Js->submit(__('Submit'),
				  array( 'url'=> array('controller'=>'families',
				   'action'=>'validate_add',),
				   'id'=>'submit_add_family',
				   'before'=>'var $this=$(this);',
				    'buffer' => false,
				    'class'=>$submit_class,
				    'success'=>'updateFamilyContact(data,"'.$familyrole.'");',
				    ));
			 echo $this->Form->end();
			 * 
			 */?>
</div>				






