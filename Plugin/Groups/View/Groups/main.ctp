<div id="top-area" class="container_12">

	<div class="grid_2">
		<h3>Group Staff</h3>
<?php 	 echo $this->requestAction(array('controller' => 'members', 'action' => 'index'),array('count' => '1','role_type'=>'stuff','layout'=>'minimal'));?>
	</div>
	<div class="grid_10">
	</div>
	<div class="clear">	</div>
</div>