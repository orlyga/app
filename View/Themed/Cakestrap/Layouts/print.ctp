<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php //echo $this->Seo->meta();
		//if title is not set in Seo then title is the seen title of the page
		//if(!$this->Seo->isTitleSet())
		if (!isset($title_for_layout)) $title_for_layout="";
			echo '<title>'.$title_for_layout.' &raquo; '.Configure::read('Site.title').'</title>';
		echo $this->Layout->feed();
		
?>
<script type="text/javascript">
function ajax_failed(text){
	if(text=='Forbidden'){
		window.location="/";
	}
}
function myCallback(i, width) {
  // Alias HTML tag.
  var html = document.documentElement;

  // Find all instances of range_NUMBER and kill 'em.
  html.className = html.className.replace(/(\s+)?range_\d/g, '');

  // Check for valid range.
  if (i > -1) {
    // Add class="range_NUMBER"
    html.className += ' range_' + i;
  }

  // Note: Not making use of width here, but I'm sure
  // you could think of an interesting way to use it.
}

</script>
<?php
		echo $this->Layout->js();
		
		echo $this->Html->script(array(
		'jquery/jquery.min.js',
		'jquery/superfish',
		'jquery/supersubs',
		'jquery/jquery.printElement.min',
		));
			echo $this->Html->css(array('reset','basic','theme',));
		if (Configure::read('Site.locale')=='iw'){
					echo $this->Html->css('hebrew');
			}
		echo $this->Html->css('print',null, array('media' => 'print'));
		
	echo $scripts_for_layout;	
?>

<script type="text/javascript">

</script>	

	</head>
	<body>
		<div id="wrapper"  class="container_12">
			<div  class="grid_12">
				<?php echo $this->Html->link(__('Print'),"#",array('onClick'=>"javascript:$('#main_print').printElement();return false;",'class'=>'button-elegant'));?>

			</div>
			<div class="clear"></div>
			<div  class="grid_1"></div>
			<div id="main_print" class="grid_10">
				<div style="width:800px">
						<?php echo $content_for_layout;	?>
				</div>
			</div>
			<div  class="grid_1"></div>
			<div class="clear"></div>
		</div>

<?php		

		echo $this->Js->writeBuffer(array('inline' => true,));
?>

	
	</body>
</html>