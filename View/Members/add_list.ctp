<!-- members/add_list.ctp -->
<div class="members form">
<?php echo $this->Form->create('Contact',array('type'=>'file'));?>
	<fieldset>
	<?php	
		if (!isset($count)) $count=1;
		$i=0;
		while ($i < $count) {
			/* echo $this->element('contacts/form_min', array('index'=>$i,'fieldset'=>array(
					'city' => array('type' => 'hidden','class' => "crue_city"),
					'address'=>array('type' => 'hidden','secure' => 'false'),
					'image'=>array('type'=>'hidden'),
					)));
		echo $this->Form->input('Member.'.$i.'.grouprole_type',array('value' => 'staff','type' => 'hidden'));
		echo $this->Form->input('Member.'.$i.'.contact_id',array('type'=>'hidden','value'=>''));
		echo $this->Form->input('Member.'.$i.'.group_id',array('type'=>'hidden'));
		echo $this->Form->input('Member.'.$i.'.request_status',array('type'=>'hidden','id'=>'request_status_id'));
		
		 * /
		 * 
		 */
		 echo $this->element('contacts/form_min', array('fieldset'=>array(
					'city' => array('type' => 'hidden','class' => "crue_city"),
					'address'=>array('type' => 'hidden','secure' => 'false'),
					'gender'=>array('type'=>'hidden'),
				'birthdate'=>array('type'=>'hidden'),
					'image'=>array('type'=>'hidden'),
					)));
		 echo $this->Form->input('Member.'.'.grouprole_type',array('value' => 'staff','type' => 'hidden'));
		echo $this->Form->input('Member.'.'.contact_id',array('type'=>'hidden','value'=>''));
		echo $this->Form->input('Member.'.'.group_id',array('type'=>'hidden','value'=>'46'));
		echo $this->Form->input('Member.'.'.request_status',array('type'=>'hidden','id'=>'request_status_id'));
		 
		 $i++;
		}
?>
</fieldset>
<?php 
 echo  $this->Js->submit(__('Submit',true),
	  array( 'url'=> array('controller'=>'members',
	   'action'=>'add'),
	    'class' => 'ajax-link',
	    'buffer' => false,
	    'success'=>'alert(data);',
	    ));
	  
 echo $this->Form->end();?>

</div>

