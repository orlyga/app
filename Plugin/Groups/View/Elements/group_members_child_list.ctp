<script>
$(function(){
var name = $("#anc2").attr("href");
name=$(name).find(".name").html();
var n=name.indexOf("</span>")+10;
name1=name.substr(n,1);
$("#anc1").html('×�-'+name1);
 name = $("#anc3").attr("href");
name=$(name).find(".name").html();
 n=name.indexOf("</span>")+10;
name2=name.substr(n,1);
$("#anc2").html(name1+'-'+name2);

     name = $("#anc4").attr("href");
name=$(name).find(".name").html();
 n=name.indexOf("</span>")+10;
name3=name.substr(n,1);
$("#anc3").html(name2+'-'+name3);

$("#anc4").html(name3+'-×ª');
});
</script>
<?php 
$gap_anchor=floor(count($child_members)/4);
?>
<div id="childmember" class="members index" >
	<a id="anc1" href="#tr_1" class='btn' ><?php echo __("a-h")?></a>
	<a id="anc2" href="#tr_<?php echo $gap_anchor;?>" class='btn'> </a>
	<a id="anc3" href="#tr_<?php echo $gap_anchor*2;?>" class='btn'> </a>
    <a id="anc4" href="#tr_<?php echo $gap_anchor*3;?>" class='btn'> </a>

</div>
<?php $num=count($child_members);
		$num1=ceil($num/2);
		$child_members1=array_slice($child_members,0,$num1);
		$child_members2=array_slice($child_members,$num1);

?>
		<?php if (!isset($half_second)) { ?>
		<div  class="two_colums span5"  style="margin: 0;">
		<?php
			echo $this->element("Groups.inner_child_list",array("members"=>$child_members1));
		?>
		</div>
		<?php } ?>
		<?php if (!isset($half_first)) { ?>
		<div  class="two_colums span5" style="margin: 0;margin-right: 10px;">
		<?php
		 echo $this->element("Groups.inner_child_list",array("members"=>$child_members2,"local_count"=>$num1));
				?>
		</div>
		<?php } ?>

