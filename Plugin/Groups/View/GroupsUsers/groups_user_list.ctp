<script>
function switch_group(group_id){
window.location="<?php echo $this->Html->url('/viewgroups')?>/"+group_id;
}
</script>

			<div id="group-list" class="button-list span4"><ul class='list'>

			<?php 
					foreach ($groups as $group){
						?>
						<li><a href="#" onClick="switch_group(<?php echo $group['GroupsUser']['group_id']*$session?>);return false;" class=""><h3><?php echo $group['Group']['name']?></h3></a></li>
			<?php }
					echo '</ul>';
					?>
		</div>
		
