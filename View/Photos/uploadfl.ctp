<script type="text/javascript">

function setuploadresult(json1){
	
	if (json1.type=="error")
		$("#printerror").html(json1.message);
	else
	{
		
		$("#printerror").html(json1.message);
			
            return false;

	}
}
</script>
<div id="printerror"></div>
<div class="printing form">
	<?php 
 echo $this->Form->create('Photo',array('type'=>'file'));?>
		<fieldset>
		<?php
		echo $this->Form->file('image');
		
		?>
		</fieldset>
		<?php
		 echo  $this->Js->submit(__('Upload',true),
		 array('before'=>$this->Js->get('#sending')->effect('fadeIn'),
	  'url'=> array('controller'=>'photos',
	   'action'=>'uploadfl'),
	    'class' => 'ajax-link button-elegant',
	    'buffer' => false ,
	    'success' => 'setuploadresult(data)'
	      ));

	 echo $this->Form->end();?>


