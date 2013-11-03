<div >
			<h4><?php echo __("Sign up with your Email Address"); ?></h4>
				<?php echo $this->Form->create('User', array('autocomplete'=>'on','url' =>"/register"));?>
			<fieldset>
			<?php
				$this->Form->inputDefaults(array(
				'label' => false,
				'autocomplete'=>'on',
			));
					echo $this->Form->input('email', array(
						'placeholder' => __d('croogo', 'Email'),
						'before' => '<span class="add-on"><i class="icon-user"></i></span>',
						'div' => 'input-prepend text',
						
					));
				echo $this->Form->input('username',array('type'=>'hidden'));
				echo $this->Form->input('password', array(
						'placeholder' => 'Password',
						'before' => '<span class="add-on"><i class="icon-key"></i></span>',
						'div' => 'input-prepend password',
						
					));
				echo $this->Form->input('name',array('required' => 'true','placeholder'=>__('Your Full Name')));
				//echo $this->Form->input('website',array('placeholder'=>__('Full Name')));
			?>
			</fieldset>
			<?php echo $this->Form->submit(__('Register'),array('style'=>'width:100%','class'=>'btn green_gradient'));?>
			<?php echo $this->Form->end();?>
		</div>