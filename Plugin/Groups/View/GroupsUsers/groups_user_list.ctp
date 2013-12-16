<script>
function switch_group(group_id){
window.location="<?php echo $this->Html->url('/viewgroups')?>/"+group_id;
}
</script>

			<div id="group-list" class="button-list span4"><ul>

			<?php 
					foreach ($groups as $group){
						?>
						<li><a href='/viewSwitch/<?php echo $group['GroupsUser']['group_id']*$group['GroupsUser']['group_id'] ?>' class="btn btn-block"><?php echo $group['Group']['name']?></a></li>
			<?php }
					echo '</ul>';
					?>
		</div>
