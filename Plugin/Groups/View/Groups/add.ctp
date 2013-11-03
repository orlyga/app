<?php echo $this->Html->script('jquery.validate');

?>
<script>
function add_step(div_id){
	$('#'+div_id).removeClass('hide');
}
function after_add_child(){
submitAddForm();
}

function submitAddForm(ev){
	//ev.preventDefault();
	ev.target.checkValidity();
}
</script>

<div class="groups form span5">
	<?php echo $this->croogoForm->create('Group',array('type'=>'file'));
 echo $this->croogoForm->input('redirect',array('type'=>'hidden','value'=>'/invitetogroup'));
 echo $this->form->input('stam',array('id'=>'stam'));
 echo $this->element('Groups.group_form',array('parent'=>'Group'));
echo  $this->croogoForm->submit(__('Continue'),array('class'=>'btn green_gradient','style'=>'width:100%'));
echo  $this->croogoForm->end();
echo '</div>';

?>
</div>



