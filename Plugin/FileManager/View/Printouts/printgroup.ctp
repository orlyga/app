<script>
var closeit=false;
window.onfocus=function(){ 
	
	//window.close();}
$(function(){
	
		//$('#print_group_version').printThis();
 closeit=true;
})
</script>
<div style="width: 90%;margin: auto;">
<?php
$this->print=true;
$this->back_button= "<a href='".$this->html->url("/groupsview")."'><span>".__('Back')."</span></a>";
echo $this->Html->script(array(
		'jquery.printelement.min',
		));
?><div><br/><?php
echo $this->Html->link(__('Print'),"#",array('class'=>'btn btn-large green_gradient','style'=>"width:initial",'onClick'=>"javascript:$('#print_group_version').printThis();return false;"));
?></div>
<?php
 $content_for_layout1= $this->element("Groups.group_members_child_list",array("members"=>$child_members,'half_first'=>true));
 $content_for_layout2= $this->element("Groups.group_members_child_list",array("members"=>$child_members,'half_second'=>true));

//$content_for_layout= str_replace("[","<",$content_for_layout);
//$content_for_layout= str_replace("]",">",$content_for_layout);
//$content_for_layout= str_replace("]",">",$content_for_layout);
?>
<div id='print_group_version' style='width:100%'>
<div id="print_header"><span class="pull-left-rtl" style="width:40%;text-align:right">  Be-member.com נוצר דרך אתר <?php echo $this->Html->image('logo.png'); ?></span></div>

	<div class="row" style="text-align:right">
		<h4><?php echo $group['Group']['name'].", ".Date('Y');?></h4>
		</div>
<div  class="two">
<?php

	echo $content_for_layout1;
?>
</div>
<div  class="two">
<?php

	echo $content_for_layout2;
?>
</div>
</div>
<div class='clear'></div>
<br/>
<div><?php
echo $this->Html->link(__('Print'),"#",array('class'=>'btn btn-large green_gradient','style'=>"width:initial",'onClick'=>"javascript:$('#print_group_version').printThis();return false;"));
?></div></div>