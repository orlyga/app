<script>
$(function() {
	$("input[name$='[term_id]']").change(function() {
	   switch ($(this).val())
	   {
	   	/*school class doesnt need address or phone but need to set the school name and grade*/
	   	case '5':
	   		$("#school").show();
	   		$("#default_fields").hide();
	   		$("textarea[name$='[address]']").hide();
	   		$("input[name$='[phone]']").hide();
	   		break;
	   	case '4':
	   	
	   	default :
	   		$("#school").hide();
	   		$("#default_fields").show();
	   $("textarea[name$='[address]']").show();
	   		$("input[name$='[phone]']").show();
	   		$("input[name$='[name]']").val('');
	   		$("input[name$='[name]']").attr('placeholder','<?php echo __('Group Name')?>');

	   		break;	
	 
	   	
	   }
	});
	$("input[name$='[school]']").change(function() {
	   var name= 'בית ספר '+ $("input[name$='oup][school]']").val() +' <?php echo __('Class')?> '+
	   $("input[name$='[class][school]']").val();
	   $("input[name$='[name]']").val(name);
	});	   
});
</script>
<h2><?php echo __("Contact Group Details")?></h2>
	<?php
	if (!isset($parent)) $parent=null;?>
	<div class='legend-inline-div'><legend><?php echo __("Group Type",true)?></legend></div>
	<?php	echo $this->CroogoForm->input('Group.term_id',array('div'=>array('class'=>"radio-vertical",'style'=>'min-width: 100px;display:inline-block'),'default' => '4','type'=>'radio','legend'=>false));
		?><div id='default_fields' ><?php
		echo $this->CroogoForm->input('Group.name',array('placeholder'=>__('Day care Name'),'label'=>false));
		?></div><div id='school' class='hide'><?php
		echo $this->CroogoForm->input('Group.school',array('div'=>array('class'=>'input-inline'),'label'=>false,'placeholder'=>__('School Name')));
		echo $this->CroogoForm->input('Group.class.school',array('div'=>array('class'=>'input-inline'),'class'=>'input-small','label'=>false,'placeholder'=>__('Class')));
		?></div><?php
		echo $this->croogoForm->input('Group.contact_id',array('type'=>'hidden'));
	?>
	
<?php  echo $this->element('Contacts.form', array('parent'=>$parent,'contact_type'=>'group'));
?>