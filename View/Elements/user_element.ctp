<div class="users form">
    <h2><?php __('Login'); ?></h2>
    
    <?php  echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));?>
        <fieldset>
        <?php
            echo $this->Form->input('username',array('label'=>__('User Name',true),));
            echo $this->Form->input('password',array('label'=>__('Password',true),));
        ?>
        </fieldset>
    <?php echo $this->Form->end('Submit');?>