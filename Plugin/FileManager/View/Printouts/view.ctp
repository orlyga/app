		<?php 
		echo $this->Form->create('Import',array('url'=>array('plugin'=>'file_manager','controller'=>'printouts','action'=>'printpreview')));
			echo $this->Form->input('name', array('label' => __('Invitaion from'), ));
			echo '<div class="error_message"></div>';
			 $tiny= $this->Tinymce->input('message', array( 
		
            'label' => 'Content' , 'value'=>$body
           
            ),array( 
                'language'=>'he' ,
                'directionality' => "rtl",
                 'width'=>'600px',
                
            ), 
            'basic' );
			echo $this->Form->input('amount',array('value'=>0,'required'=>true));
			//$tiny=str_replace('</textarea>',$body."</textarea>",$tiny);
			echo $tiny;
			$this->Form->unlockField("message");
			 echo $this->Form->end(__('Submit',true) ); 
		
	?>		