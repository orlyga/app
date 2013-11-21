<div class="nav-collapse" style="display:inline-block;position:absolute">
	<section>
	  	<ul class="nav">
		<?php if ($loggeduser){
							//////////////for logged users//////////?>
								 <li class="dropdown left_hr">
								 	  <a data-toggle="dropdown" class="dropdown-toggle btn green_gradient" href="#"><?php echo __('Actions')?></a>
									 	<ul class="dropdown-menu">
									 		<?php if (isset($this->menu_actions_addition)) echo $this->menu_actions_addition;?>
	
										 		<li class="dropdown submenu">
										 			<a href='<?php echo $this->Html->url('/addgroup')?>'  class=''>
								  					<i class=' icon-plus-sign '></i><?php echo __('New Group',true)?></a>
										 		</li>
										 		<?php if ($loggeduser){?>
										  		<li class="dropdown submenu">
										  			<a href='<?php echo $this->Html->url('/logout')?>'  class=''>
										  			<i class="icon-off icon-white"></i><?php echo __('Logout',true)?></a>
												</li>
												<?php }?>
										</ul>
								</li>	
								<?php if (isset($this->menu_addition)) {
									$this->menu_addition=str_replace('dropdown-toggle','dropdown-toggle btn green_gradient',$this->menu_addition);
														echo $this->menu_addition;
											}?>
								
						<?php } else {
							//////////////for unlogged users//////////?>
							<li class="left_hr">	
								<?php echo $this->element('Users.login');?>
						    </li>   
							<li class="left_hr">
								 <a href='<?php echo $this->Html->url('/addgroup')?>'  class='text-bold-light'>
						  		<i class=' icon-plus-sign icon-white icon-2x '></i><?php echo __('New Group',true)?></a>
							</li>
							
						    <!--<li class="left_hr">
								<a href='<?php echo $this->Html->url('/register')?>'  class=''>
						  			<i class='icon-pencil icon-white'></i><?php echo __("New Account");?></a>
							</li> 	-->
							<?php } ?>
			  	 </ul>  
	</section>   
</div>