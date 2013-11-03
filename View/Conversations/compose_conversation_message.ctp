<?php 
	echo $this->Html->css('/css/chosen.jquery');
	echo $this->Html->script('/js/chosen.jquery.min');
	
?>
<script>
	jQuery(document).ready(function($) {
		$('.i-select').chosen();
	});
	
</script>
<?php
	echo $this->Form->create('Conversation', array('action' => 'composeConversationMessage'));
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
	echo $this->Form->input('Conversation.id', array('type' => 'hidden', 'value' => $id));
	echo $this->Form->input('ConversationsMessage.0.message', array( 
            'label' => __('Content'),'type'=>'textarea')  
        ); 
	echo '<div id="submit_message" >';
		 echo  $this->Js->submit(__("Submit"),
	  array( 'url'=> array('controller'=>'conversations',
	   'action'=>'composeConversationMessage',$id),
	   'before'=>'var $this=$(this);',
	    'class' => 'ajax-link button-elegant',
	    'buffer' => false,
	    'success'=>'updateMessage('.$id.');',
	    'error'=>'ajax_failed(data)'
	      ));
echo '</div>';	  
 echo $this->Form->end();?>
		
