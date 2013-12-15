
		<?php
			echo $this->Html->meta('icon');
			
			echo $this->fetch('meta');
			echo $this->Layout->feed();
			echo $this->Html->css('bootstrap');
			echo $this->Html->css('bootstrap-responsive');
			echo $this->Html->css('core');

			echo $this->fetch('css');
			?>
			 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript" ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.min.js" type="text/javascript"></script>
<?php
			echo $this->Html->script('libs/jquery');
			echo $this->Html->script('main');
			echo $this->Html->script('libs/bootstrap');
			echo $this->Html->script("bootbox.min");
			//echo $this->fetch('script');

			echo $this->Layout->js();
		echo $this->Blocks->get('css');
		echo $this->Blocks->get('script');
		?>
	</head>

	<body >
<script>
function setinputfield(feildId,value){
	$('input[id*="'+feildId+'"]').val(value);
}
</script>