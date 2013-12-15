	<script>
	function validate_print(){
		var val = $("#ImportAmount").val();
		if (val==0) {
    // inputted file path is not an image of one of the above types
    $(".error_message").html("<?php echo __('Please insert Amount')?>");
        return false;
	}
	return true;
	}
	</script>
		<?php 
		global $tiny;
		echo $this->Form->create('Import',array('url'=>array('controller'=>'groupsusers','action'=>'invite'),'onSubmit'=>'if(!validate_print()) return false;'));
			echo $this->Form->input('name', array('label' => __('Invitaion from'), ));
			echo '<div class="error_message"></div>';
			echo $this->Form->input('amount',array('value'=>0));
			$tiny=str_replace('</textarea>',$body."</textarea>",$tiny);
			echo $tiny;
			$this->Form->unlockField("message");
			 echo $this->Form->end(__('Submit',true) ); 
		//if ($print_num>=$i) {
			//	for($i;$i<=$print_num-1;$i++){
				//	$text=$txt_header."__________________________".$txt_footer;
				//	$str .= $this->set_printout($i,$divs_per_page,$text);
				//	}
		//	}
			/*if (mysqli_num_rows($result) > 0) {
				//while($trimmed = mysqli_fetch_assoc($result)) {
				//	if ($i> $print_num)	break;
				//	$replaced_str=" ".$trimmed['childName']. " " .$trimmed['con_ser']." ";
				//	$text=$txt_header.$replaced_str.$txt_footer;
				//	$str .= $this->set_printout($i,$divs_per_page,$text);
				//	$i++;
				//}
			}
			if ($print_num>=$i) {
				for($i;$i<=$print_num-1;$i++){
					$text=$txt_header."__________________________".$txt_footer;
					$str .= $this->set_printout($i,$divs_per_page,$text);
					}
			}
		}*/
	?>		