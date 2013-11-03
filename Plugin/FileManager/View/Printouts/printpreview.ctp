<script>

var closeit=false;
window.onfocus=function(){ 
	
	window.close();}
$(function(){
	
		$('#main_print').printThis();
 closeit=true;
})
</script>
<?php
echo $this->Html->script(array(
		'jquery.printelement.min',
		));
?><div><br/><?php
echo $this->Html->link(__('Print'),"#",array('class'=>'btn btn-large green_gradient','onClick'=>"javascript:$('#main_print').printThis();return false;"));
?></div><?php
$content_for_layout= str_replace("[","<",$content_for_layout);
$content_for_layout= str_replace("]",">",$content_for_layout);
$content_for_layout= str_replace("]",">",$content_for_layout);
?>
<div id='main_print'>
<?php
	echo $content_for_layout;
?>
</div>
<div><br/><?php
echo $this->Html->link(__('Print'),"#",array('class'=>'btn btn-large green_gradient','onClick'=>"javascript:$('#main_print').printThis();return false;"));
?></div>