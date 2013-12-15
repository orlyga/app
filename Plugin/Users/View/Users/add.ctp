<script>
$(function() {
if($("#add_user_form").find(".error-message").length>0)
{
	 $("#add_user_form").find(".error-message")[0].focus();
}

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

<h3><?php echo __('Register to Be-member');?></h3>

<br/>
<div class="span5" >
		<div id="secondary" class="collapse-group">
			<a href="#" id='fb_register_button'  class='btn fb_register'>
					<span class="add-on"><i class="icon-facebook icon-2x icon-white" style=""></i></span><?php echo __("Register with facebook");?>
			</a>
			<div id="fb-register" class="collapse">
							<?php 	if (!empty($fb_user)) echo $this->Facebook->registration(array('loggedin'=>$fb_user,'redirect-uri' => 'http://be-member.com/facebook_register',));
							  else { echo '<div class="alert-error">'.__('Please Connect to FaceBook ').'</div><br/>'. $this->Facebook->login(array('redirect'	=> $this->html->url('/facebook_register')));} ?>
			</div>
			<div class="texton-line">
				<p ><?php echo __('OR')?></p>	
			</div>
			<div id="add_user_form">
						<?php echo $this->element('Users.register');?>
			</div>			
		</div>
</div>
	
	
	
