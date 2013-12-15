<?php 
	echo $this->Html->css('/css/chosen.jquery');
	echo $this->Html->script('/js/chosen.jquery.min');
	
?>
<script>
	jQuery(document).ready(function($) {
		$('.i-select').chosen();
	});
function beforeSubmit(){
	$type = $("#ConversationsType1").val();
	if ($type==4)	$x= before_save_choose_memeber("RecipientsTo_");
	return $x;
}
function show_modal(){
		if ($("#list_recipients").attr('disabled')) return false;
	$("#modal").show();
	window.location.href="#modal";
	}
function setrecipients(to){
	if(to==4){ 
		$("#list_recipients").removeAttr('disabled');
		$("#list_recipients").removeClass("transparent");
		}
	else
	{
		$("#list_recipients").attr('disabled', 'disabled');
		$("#list_recipients").addClass("transparent");
	}
}
</script>
<?php
	echo $this->Form->create('Conversation', array('action' => 'composeNew','onSubmit' => 'javascript:beforeSubmit();'));
	echo $this->Form->create('Conversation', array('action' => 'composeConversationMessage','onSubmit' => 'return before_save_choose_memeber("RecipientsTo_");'));
	if (isset($contacts[0])){
			  $this->element("families/dropdown_familylist");
				 echo $this->Form->input( 'ConversationsMessage.0.contact_id', array(
		        'type' => 'select',
		        'options' => $this->contact_array,
		        'empty' => false,
		        'label'=> __("From")
		    ));
		}
	else 
		echo $this->Form->input('ConversationsMessage.0.contact_id', array('type' => 'text', 'value' => $contacts['id'],'placeholder' => $contacts['name']."  ".$contacts['last']));
	
		$options = array('1' => __('Members and Staff'), '2' => __('Members Only'),'3'=>__('Staff Only'),'4'=>__('Select from Group'));
$attributes = array('legend' => __('Send To:'),'separator'=>'<br/>','onClick'=>'javascript:setrecipients(this.value);',
		        'value'=>2);
echo '<div id="radio">';
echo $this->Form->radio('Conversations.Type', $options, $attributes);
echo '</div><div id="list_recipients" disabled class="transparent">';
		echo $this->Form->input('Recipients.to', 
			array(
				'legend'=>false,
				'type' => 'select', 
				'div'=>array('onClick'=>"javascript:show_modal();"),
				'multiple' => true,
				'class' => 'i-select', 
				'data-placeholder' => __('Recipients'), 
				'tabindex' => 1,
				'width'=>200,
				'options' => $memberRecipients

			));
		echo '<hr />';
		echo '</div>';
		echo $this->Form->input('Conversation.title', array('type' => 'text', 'label' => 'Subject'));
		echo $this->Tinymce->input('ConversationsMessage.0.message', array( 
            'label' => 'Content' 
            ),array( 
                'language'=>'he' 
            ), 
            'full' 
        ); 
		
	echo $this->Form->end('Send');
	?>
	<div class="modal" id="modal" width="100%">
	
	<?php echo $this->element("members/choose_members");?>
	</div>
