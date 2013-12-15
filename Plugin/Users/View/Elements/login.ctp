	<script>
	$(function(){
		 $('#login-popover').popover({
			html:true,
			title:function(){return $(this).parent().find('.popover-title').html();},
			content:function(){return $(this).parent().find('.popover-content').html();},
			placement:'bottom',
			container: 'body'
		});
	});
	</script>
		<a href="#" id="login-popover" rel="popover" class="text-bold-light" ><span style=""><i class="icon-user icon-white icon-2x"></i><?php echo __("Login") ?></span></a>
		<h3 class="popover-title hide"><?php echo __("Login") ?></h3>
		<div class="popover-content hide">
			<?php echo $this->element('Users.login_form',array("formid"=>'popLoginForm'))?>
		</div>
	  

  
	
	