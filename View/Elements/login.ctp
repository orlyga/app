<div id="loginuser" class="users form">
    <?php $mode=isset($mode) ? $mode :"";
    if (isset($error)){
    	echo "<div class='text-error'>".__("User and Password do not match")."</div>";
    } ?>
    <?php  echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login',$mode)));?>
       
        <?php
            echo $this->Form->input('username',array('div'=>false,'class'=>'span2','label'=>false,'placeholder'=>__('User Name',true),));
            echo $this->Form->input('password',array('div'=>false,'class'=>'span2','label'=>false,'placeholder'=>__('Password',true),));
        ?>
       
        <?php
       
  /*  if ($mode<>"") {
 echo  $this->Js->submit(__('Next',true),
	  array('before'=>$this->Js->get('#sending')->effect('fadeIn'),
	  	    'class' => 'ajax-link button-elegant',
	    'buffer' => false  ,
	    'id'=>"submit_login",
	    'success'=>'updateUserId(data,"'.$mode.'")'
	     ));
		 echo $this->Form->end();
	
	}
	else*/
     echo $this->Form->end(array('div'=>false,'class' => 'ajax-link btn','label' => __('Sign in'),'id'=>'submit_login',));
 echo $this->Html->link(__('Forgot password?', true), array(
            'admin' => false,
            'controller' => 'users',
            'action' => 'forgot',
        ), array(
            'class' => 'forgot',
        ));?>
    <?php echo $this->Html->link(__("Register"),array(
            'admin' => false,
            'controller' => 'users',
            'action' => 'add')); ?>
    </div>