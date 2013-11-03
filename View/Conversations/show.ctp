<script>
function composeNewResponse(){
	$("#respond_button").hide();
	$("#respond_form").show();
}
</script>
<?php
	if(isset($this->request->params['named']['show'])) {
		debug($convers);
	}
?>

<?php 
	//echo //$conversation['Conversation']['title']; 
?>
<ul>
<?php foreach($convers['Messages'] AS $message) { ?>
	<?php $float='left';
	$mine="";
	$margin_left="margin-left:-70px";
	if ($message['ConversationsMessage']['member_id']==SessionComponent::read('Group.MemberId')) {$margin_left=""; $float='right';$mine="mine";}
	$class= $terms[$message['ConversationsMessage']['message_type_id']];
	//if ($class=="summery");
	?>
		
		<li class="comment_msg" style="float:<?php echo $float?>">
			<div class="comment_msg_metta" style="float:<?php echo $float.";".$margin_left?>"><?php echo $message['Member']['Details']['name']."  ". $message['Member']['Details']['last']."  <span class='small_text'>". $message['ConversationsMessage']['created']; ?></span> 
				<?php 
				if ($message['Member']['Details']['image'] <> null){ 
					$fn=substr(strrchr($message['Member']['Details']['image'], "/"), 1);
					$fn=str_replace($fn,"thumb_".$fn,$message['Member']['Details']['image']);
					echo $this->Html->image($fn,array('width'=>40)) ;
					}
				else 
					echo $this->Html->image("photos/anonymous_child.jpg",array('width'=>40)) ;
				if (($class=='main')&&($mine=="mine"))
					{
						echo '<div id="add_summery">';
								//echo  $this->Html->link(__("Tentative Summery"),array('controller'=>'conversations','action'=>'composeNew','summery'),array('class'=>'button-inline-action'));
						echo '</div>';
					}
				?>
				</div>
			<blockquote class="<?php  echo $class." ".$mine;?>">
 				<p>
				<?php echo $message['ConversationsMessage']['message']; ?>  
				</p>
 			</blockquote>
			</li>
<?php }

 ?>
</ul>
<?php
echo '<div id="respond_button">';
		echo  $this->Html->link(__("Respond"),'javascript:composeNewResponse();',array('class'=>'button-inline-action'));
 echo '</div>';
 echo '<div id="respond_form" style="display:none">';		
 		echo $this->requestAction(array('controller' => 'conversations', 'action' => 'composeConversationMessage',$id));
echo '</div>';	
?>	