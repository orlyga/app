<?php if (!isset($formid)) $formid="UserLoginForm";?>
<script>
jQuery(function($) {
    $("#<?php echo $formid ?>-button").bind("click",function(){
    	$("#<?php echo $formid ?>").submit();
    });
});
</script>
<?php if (isset($show_registration_button)){
				echo'<span class="comment">'.  __("New in Be-member?")?></span>
				<a href='<?php echo $this->html->url('/register')?>' style="width:90%" id="new-account-btn" class='btn btn-large green_gradient'>
					<?php echo __("New Account");?>
			</a>
			<br/>			<br/>
			<h5>משתמש קיים</h5>
			<?php } ?>
<div class="fb-login-button "><?php //localhostxx 
	echo $this->Facebook->login(array(
		'redirect'=>$this->html->url("/facebook_login")
		
		)); ?>
		<span class="comment"><?php echo __("Connect Be-member using facebook login");?></span>
	</div>
<hr>
<div  class="users form">
	<span class="comment"><?php echo __('Connect Be-member with your email and password');?></span>
				<?php $mode=isset($mode) ? $mode :"";
				echo $this->Form->create('User', array('id'=>$formid,'url' => '/login'));
				$this->Form->inputDefaults(array(
			'label' => false,
		));
				echo $this->Form->input('User.username', array(
					'placeholder' => __d('croogo', 'Email'),
					'before' => '<span class="add-on"><i class="icon-user"></i></span>',
					'div' => 'input-prepend text',
				));
				echo $this->Form->input('User.password', array(
					'placeholder' => 'Password',
					'before' => '<span class="add-on"><i class="icon-key"></i></span>',
					'div' => 'input-prepend password',
				));
			echo $this->Form->input('remember', array(
				'label' => __d('croogo', 'Remember me?'),
				'type' => 'checkbox',
				'default' => true,
				
			));	
	    echo $this->Form->Submit(__('Log in'),array('div'=>array(),'class'=>'btn green_gradient'));
		 echo $this->Form->end();?>
	
	
<span class='comment'><?php
			//	echo '<p>'.$this->Form->end(array('div'=>false,'class' => 'btn green_gradient','label' => __('Sign in'),'id'=>'submit_login','style'=>'display: inline-block'));
				echo $this->Html->link(__('Forgot password?', true), array(
					'admin' => false,
					'plugin' => 'users',
					'controller' => 'users',
					'action' => 'forgot',
					), array(
						'class' => 'forgot',
				)).'</span>';
			
			?>
			</div>
			<hr/>
			
			