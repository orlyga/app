
<?php

if (isset($this->params['named']['familyrole'])) $familyrole=$this->params['named']['familyrole'];
if (isset($this->params['named']['index'])) $index=$this->params['named']['index']; else $index="";
$email=false;
$hostingdiv="";
if ($familyrole==8){
	$email=true;
	$hostingdiv='replacefirst';
	$submit_class="button-elegant";
}
if ($familyrole==9){
	$hostingdiv='secondparent';
		$submit_class="hide";
	
}
 echo $this->Form->create('Contact');
 				echo $this->Form->input('Family.id',array('type'=>'hidden'));
			   	echo $this->Form->input('Family.request_status',array('type'=>'hidden','id'=>'request_status_id'));

 				$contact_fields=array(
			    'name'=>array('div'=>array('class'=>'input text noinline')),
				'image'=>array('type'=>'display'),
				'familyrole_id'=>array('value'=>$familyrole),
				'address'=>array('rows'=>2,'cols'=>17),
				'gender'=>array('type'=>'hidden'),
				'birthdate'=>array('type'=>'hidden'),
				'email'=>array('readonly'=>$email))				;
				
				echo $this->element('contacts/form', array('index'=>$index,'fieldset'=>$contact_fields,'hostingdiv'=>$hostingdiv));
				$this->Form->unlockField('Contact..city_id');
				$this->Form->unlockField('Contact.family_id');
				$this->Form->unlockField('ContactFamily.familyrole_id');
				$this->Form->unlockField('Family.id');
				echo  $this->Js->submit(__('Submit'),
					  array( 'url'=> array('controller'=>'families',
					   'action'=>'add'),
					   'before'=>'var $this=$(this);',
					    'buffer' => false,
					    'class'=>$submit_class,
					    'success'=>'updateFamilyContact(data,'.$familyrole.');',
					    ));
				 echo $this->Form->end();?>
				






