<script>
function add_child(){
	
	if (typeof click_add_child ==='function')
		click_add_child();
}
function click_child_selected(contact_id){
	if (typeof child_selected ==='function')
		child_selected(contact_id);
}
</script>
<?php if(empty($children)) return; ?>
<div id="contact-children">
	<h5><?php echo __('Add or Select Who is going to be in the Group')?></h5>

	<?php 
	
	$add_child=$this->element('Contacts.AddChild',array('parent'=>$parent.'.Child',$contact_parent_id))	;
	if(!empty($children)){ ?>
		<div class="button-list"><ul style='width:50%;margin:0'>
			<?php	
				echo '<li><i class="icon-plus"></i>'.$this->Html->link(__('Add Child'), 'javascript:add_child()',array('class'=>'btn btn-warning')).'</li>';
			
					foreach ($children as $child){
						?>
						<li><a href="#" onClick="click_child_selected(<?php echo $child['Contact']['id']?>);" class="btn btn-block"><?php echo $child['Contact']['name']." ".$child['Contact']['last']?></a></li>
			<?php }
					echo '</ul>';
					
					
					?>
		</div>
		
<?php
	}
	//there are no children, show add children form, cause it has to have a child 
?>

</div>
