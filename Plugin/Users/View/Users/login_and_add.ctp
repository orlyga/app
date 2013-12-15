<script>
$(function() {
if($("#add_user_form").find(".error-message").length>0)
{
	 $('#main_collapse').collapse('toggle');
	 $("#add_user_form").find(".error-message")[0].focus();
}
$('#new-account-btn').on('click', function(e) {
    e.preventDefault();

    $('#main_collapse').collapse('toggle');
});
$('#fb_register_button').on('click', function(e) {
    e.preventDefault();
     $('#fb-register').collapse('toggle');
    
});
	$("input[name$='[email]']").change(function() {
	   var $name = $(this).closest('form').find("input[name$='[username]']").val($(this).val());
	});
});
</script>
<div class='row-fluid' style='width: 80%;'>

<h3><?php echo __('Enter Be-member');?></h3>

<br/>
	
	<div class="span5" >
	<h5><?php// echo __("Already have an account?")?></h5>
	<?php echo $this->element('Users.login_form');?>
	<div id="main" class="collapse-group">
	<hr>
	<h5><?php echo __("New in Be-member?")?></h5>
		<a href="#" style="width:100%" id="new-account-btn" class='btn btn-large green_gradient'>
					<?php echo __("New Account");?>
			</a>
		<div id="main_collapse" class="collapse">	
			<div id="secondary" class="collapse-group">
			<br/>
				<a href="#" id='fb_register_button' style="color:white;background-image:none;background-color: rgb(72, 99, 174);" class='btn fb_register'>
						<span class="add-on"><i class="icon-facebook icon-3x icon-white" style=""></i></span><?php echo __("Register with facebook");?>
				</a>
				<div id="fb-register" class="collapse">
						<?php 	if (!empty($fb_user)) echo $this->Facebook->registration(array('loggedin'=>$fb_user,'redirect-uri' => 'http://be-member.com/facebook_register',));
						  //else { echo $this->Facebook->login(array('redirect'	=> $this->html->url('/facebook_register')));} ?>
				</div>	
				<div id="add_user_form">
					<?php echo $this->element('Users.register');?>
				</div>			
			</div>
		</div>
	</div>
</div>
	
	
