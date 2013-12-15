<div class="container_12">
	<header  class="main grid_12">
	<div id="header" class="grid_12">
		<div class="grid_4" id="logo">
		<br/>
		<ul>
		<?php
		 echo '<li>'. $this->Html->link(__('Check Out',true),'/users/logout').'</li>';
			  echo '<li>'.$this->Html->link(__('Create Group',true),array('controller'=>'registrations','action' => 'index')).'</li>';
		?>
		</ul>
		</div>
		<div class="grid_5" >
		&nbsp;
		</div>
		<div class="grid_3" >
		<?php echo $this->Html->link(
		$this->Html->image("logo.png",array('width'=>'200px',"alt"=>__('NotesGroup - Contact List',true))),'/',array('escape' => false));
		?>
	</header>
</div>
</div>