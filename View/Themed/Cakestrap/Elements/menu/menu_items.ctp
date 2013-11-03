
		<?php if ($loggeduser){
							//////////////for logged users//////////?>
								
								<!-- 	 <a <?php echo __('Actions')?></a>-->
									
									 		<?php if (isset($this->menu_actions_addition)) echo $this->menu_actions_addition;?>
	
										 		<li>
										 			<a href='<?php echo $this->Html->url('/addgroup')?>'  class=''>
								  					<?php echo __('New Group',true)?></a>
										 		</li>
										 		<?php if ($loggeduser){?>
										  		<li >
										  			<a href='<?php echo $this->Html->url('/logout')?>'  class=''>
										  			<i class="icon-off icon-white"></i><?php echo __('Logout',true)?></a>
												</li>
												<?php }?>
										
								<?php if (isset($this->menu_addition)) {
									$this->menu_addition=str_replace('dropdown-toggle','dropdown-toggle btn green_gradient',$this->menu_addition);
														echo $this->menu_addition;
											}?>
								
						<?php } else {
							//////////////for unlogged users//////////?>
							<li >	
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
			  	 