<script type="text/javascript">
 $(function() {
        $( "#accordion" ).accordion({
            heightStyle: "content"
        });
 });
function updateMessage(id){
		$("#response"+id).html("");
		expand1(id)
	}
function expand1(id) {
	var url;
	url=$("#link"+id).attr("href");
	if ($("#response"+id).html()!="") {
	return;
	}
	var callback;
    $.ajax({
        type : 'GET',
        url : url,
        params: id,
        success : function(data){
            // return data; // you cannot return data here
            $("#response"+this.params).html(data);  // should print [ Object ]
            $( "#accordion" ).accordion( "refresh" );
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            //$('#login_error').show();
            ajax_failed(errorThrown);
            alert("error :: " + textStatus + " : " + errorThrown);
        }
    });
}

</script>
<?php
	echo '<div id="new_button">';
		echo  $this->Html->link(__("New Topic"),array('controller'=>'conversations','action'=>'composeNew'),array('class'=>'button-inline-action'));
 echo '</div>';

?>
<div id="accordion">
<?php if(!empty($messages)) {
 foreach($messages AS $conversation) {
		echo '<h3>';
			 $title=$this->Html->link($conversation['Conversation']['title'],array('controller'=>'conversations','action'=>'show',$conversation['Conversation']['id']),
			 array(
			 'id'=>"link".$conversation['Conversation']['id'],
			 'onClick'=>'javascript:expand1('.$conversation['Conversation']['id'].');'));
			// $title=$this->Html->link($conversation['Conversation']['title'],array('controller'=>'conversations','action'=>'show',$conversation['Conversation']['id']));
			echo $title;

		echo '</h3>';
		//echo '<div>'; 
		//echo $conversation['ConversationsMessage']['message'];
	echo '<div id="response'.$conversation['Conversation']['id'].'"></div>';
	//echo '</div>';
	} ?>
<?php 
} else {
			echo 'No Conversations';
		}
?>
</div>