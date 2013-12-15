<?php  echo $this->Html->script('autocomplete'); 
 echo $this->Html->script('fileuploader');

?>
 <script>
 function sec_parent_set(){
 	var x=$("#sec_parent").val();
 	if (x==1){
 		$("#sec_parent").val(0);
 		$("#onoff").removeClass('on_button').addClass('off_button');
	   $("#sec_parent_fields").css("display","none");
 	}
 	else{
 		$("#sec_parent").val(1);
 		$("#onoff").removeClass('off_button').addClass('on_button');
		$("#sec_parent_fields").css("display","block");
 		
 	}
 }
 function updateFamilyContact(data,parent1){
 if($(data).find("#request_status_id").val()=="success"){
 		//need to save second parent
 		var x=$("#sec_parent").val();
 		var y=parseInt(x);
 		if ((y < 9) &&(x == 1)) {
 		 				 $("#replacefirst").html(data);
				$("#sec_parent_fields input[type='submit']").click();
				return false;
				}
		else 
			 	{
			 		window.location = '<?php echo $this->Html->url('/groups/view/')?>';
			 	return false;
			 	}

 	}
	else
	  {
	  	if($(data).find("#Contact2Id").length>0){
	  		$("#replacefirst").html($(data).find("#replacefirst").html());
	  		$("#sec_parent_fields").html($(data).find("#sec_parent_fields").html());
			return false;
	  	}
	  	if (parent1==9) $("#sec_parent_fields").html(data);
	  	if (parent1==8)  	$("#replacefirst").html($(data).find("#replacefirst").html());
	  	return false;

	  }	
 }
 </script>

 <?php 				echo $this->Form->input('Family.second',array('type'=>'hidden','id'=>'sec_parent','value'=>0));
  echo $this->Form->create('Family');
  echo $this->Form->input('Family.request_status',array('type'=>'hidden','id'=>'request_status_id'));
    echo $this->Form->input('Family.id',array('type'=>'hidden'));
  
  $i=0;
  $j=0;
$second_exit="off";
//var_dump($this->request->data['Contact']);
$x=count($this->request->data['Contact']);
while ($j < $x){
	if (!isset($this->request->data['Contact'][$i])){
		$i++;
		Continue;
	}
	$j++;
	$role=$this->request->data['Contact'][$i]['familyrole_id'];
	//-------------------------First Parent------------------------------------------------------------
	if ($terms[$role]=='first-parent') {
	?>
		<div id="firstparent" class="grid_4">
			<div class='nicebox'>
				<div class="title" ><?php echo __('First Parent')?></div>
				<div id="replacefirst">
			 		 			<?php  echo $this->requestAction(array('controller' => 'families', 'action' => 'add'),array('named'=>array('submit_button'=>true,'familyrole'=>'8','index'=>$i)));   ?>
			 		  ?>
				</div>
			</div>
			<?php $this->Form->unlockField('Family.familyrole_id');
				echo  $this->Js->submit(__('Submit'),
					  array( 'url'=> array('controller'=>'families',
					   'action'=>'edit',$this->request->data['Family']['id']),
					   'before'=>'var $this=$(this);',
					    'buffer' => false,
					    'class'=>"button-elegant absolute_center",
					    'success'=>'updateFamilyContact(data,8);',
					    ));?>
		</div>
		<div  class="grid_1"></div>
		

<?php 
	}
	else if($terms[$role]=='second-parent') { ?>
			//-------------------------Second Parent------------------------------------------------------------
		<div id="secondparent" class="grid_4 nicebox" style="position:relative">
			<div class="title" ><?php echo __('Second Parent')?></div>
				<div id="onoff" class="on_button" ><a href="javascript:sec_parent_set()" ><?php echo $this->Html->image('blank.png',array('width'=>'40px','height'=>'80px','alt'=>__('open/close')))?></a></div>
				<div id="sec_parent_fields" >
		 			<?php 
					$second_exit="on";
		 			$contact_fields=array(
					    'name'=>array('div'=>array('class'=>'input text noinline')),
						'image'=>array('type'=>'display'),
						'familyrole_id'=>array('value'=>$role),
						'address'=>array('rows'=>2,'cols'=>17),
						'email'=>array('readonly'=>true))				;
						echo $this->element('contacts/form', array('index'=>$i,'fieldset'=>$contact_fields,'hostingdiv'=>'sec_parent_fields'));
		 			   ?>
				</div>
		</div>
<?php }
$i++;
}?>



<?php

	
				 echo $this->Form->end();?>		
				
<?php if ($second_exit=="off"){
	?>
	<div id="secondparent" class="grid_4 nicebox" style="position:relative">
			<div class="title" ><?php echo __('Second Parent')?></div>
		<div id="onoff" class="off_button" ><a href="javascript:sec_parent_set()" ><?php echo $this->Html->image('blank.png',array('width'=>'40px','height'=>'80px','alt'=>__('open/close')))?></a></div>
		<div id="sec_parent_fields" style="display:none">
		<?php 		 echo $this->requestAction(array('controller' => 'families', 'action' => 'add'),array('named'=>array('submit_button'=>true,'familyrole'=>'9')));   
	echo '</div>';
}

?>
</div>
