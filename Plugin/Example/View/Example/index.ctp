<div class="example">
	<h2><?php echo $title_for_layout; ?></h2>
	<p><?php echo __d('croogo', 'content here'); 
		$this->extend('/Common/admin_index');
		
		echo $this->Form->input('Example.Basic', array('value'=>'stam','type' => 'textarea'));
?>
	</p>
</div>