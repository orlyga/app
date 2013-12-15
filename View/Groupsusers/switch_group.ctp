<div id="mem_tables" style="position:relative">
	<?php
	$i = 0;
	$options="";
	echo '<ul>';
	foreach ($groups as $group):
			$gr_link='<li>'.$this->Html->Link($group['Group']['name'],array('controller'=>'Groupsusers','action'=>'switch_group',$group['GroupsUser']['group_id'])).'</li>';
		
		if($i<3) echo $gr_link;
		else $options.=$gr_link;
		$i++;
endforeach; 
if ($options<>"") {
	echo '<li>'. $this->Html->Link(__('More'),'void(0)',array('class'=>'btn','onClick'=>'$("#moregroups").toggle();return false')).'</li>';
	echo '<div id="moregroups" style="display:none;position:absolute">'.$options.'</div>';
}
?>
</tbody>
	</ul>

</div>


