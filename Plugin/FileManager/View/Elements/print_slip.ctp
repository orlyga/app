<?php 

		echo $this->croogoForm->create('Import',array('url'=>'/printpreview','target' => '_blank'));
		echo $this->croogoForm->input('amount',array('required'=>true,'placeholder'=>__('Printing Amount')));
		echo $this->croogoForm->input('name', array('type'=>'hidden','label' => __('Invitaion from'), ));
			?><div class='box-small' style="max-width:100%"><?php echo $body?></div><?php
	echo $this->croogoForm->input('Message', 
		array('value' => $body ,'type' => 'hidden'));

			echo '<div class="error_message"></div>';
			/* $tiny= $this->Tinymce->input('message', array( 
		
            'label' => 'Content' , 'value'=>$body
           
            ),array( 
                'language'=>'he' ,
                'directionality' => "rtl",
                 'width'=>'600px',
                
            ), 
            'basic' );*/
            
			//$tiny=str_replace('</textarea>',$body."</textarea>",$tiny);
			//echo $tiny;
			$this->Form->unlockField("message");
			 echo $this->croogoForm->submit(__('Print Preview',true),array('class'=>'btn green_gradient','style'=>"width:100%;margin-top:5px") ); 
			 echo $this->croogoForm->end(); 
		
	?>		