<?php echo $this->element('header')?>
		<div id="main-container">
		
			<div id="header" class="container">
				<?php echo $this->element('menu/top_menu'); ?>
			</div><!-- #header .container -->

			<div id="content" class="container">

			<?php echo $this->Session->flash('flash',array('element'=>'flash/default')); ?>
<div id="rootwizard">
	<div class="navbar">
	  <div class="navbar-inner">
	    <div class="container">
				<?php echo $this->fetch('content'); ?>
			</div><!-- #header .container -->
			
			<?php echo $this->element('footer')?>