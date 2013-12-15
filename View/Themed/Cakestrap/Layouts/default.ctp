<?php echo $this->Html->docType('html5'); ?> 
<?php  echo $this->Facebook->html(); ?>

	<head>
		<?php echo $this->Html->charset(); ?>
				<title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo HOST_NAME?>/img/apple-icon-57x57px.png" />
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo HOST_NAME?>/img/apple-icon-72x72px.png" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo HOST_NAME?>/img/apple-icon-114x114px.png" />
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo HOST_NAME?>/img/apple-icon-144x144px.png" />
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo HOST_NAME?>/img/apple-icon-57x57px.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo HOST_NAME?>/img/apple-icon-72x72px.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo HOST_NAME?>/img/apple-icon-114x114px.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo HOST_NAME?>/img/apple-icon-144x144px.png" />
<!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
		<?php
			echo $this->Html->meta('icon');
			echo $this->Meta->meta();
			echo $this->fetch('meta');
			echo $this->Layout->feed();
			echo $this->Html->css('bootstrap.min');
			echo $this->Html->css('add2home');
			echo $this->Html->css('bootstrap-responsive.min');
			echo $this->Html->css('bootstrap_rtl');

			echo $this->fetch('css');
			?>
			 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript" ></script>
	<?php
			
			 $ip=substr($_SERVER['HTTP_USER_AGENT'],0,2);
            if (strcmp($ip,'iP'))
            $this->Html->script('add2home');
			echo $this->Html->script('libs/bootstrap');
			echo $this->Html->script("bootbox.min");
			//echo $this->fetch('script');

			echo $this->Layout->js();
		//echo $this->Blocks->get('css');
		//echo $this->Blocks->get('script');
		?>
 
 </head>

	<body >
<script>
function ajax_login($field_in_user_table,$value_field){
if (!is_login_needed()) return true;
$.ajax({
		type : 'GET',
		url : '<?php echo $this->html->url(array('plugin'=>'users','controller'=>'users','action'=>'ajax_login'))?>',
		        data:{ field: $field_in_user_table, value: $value_field },
		        success : function(data){
                  //parent page will handle results 
                        //handle partial match - notify user about mismatch
                         try{
                                var jsn=$.parseJSON(data);
								set_logged_user(jsn.user_name);
								return true;
                                }
                        //let calling function handle situation of matched user
                        catch(e){
						return false;
                            }
                     
		        },
		        error : function(XMLHttpRequest, textStatus, errorThrown) {
		            //$('#login_error').show();
		            //ajax_failed(errorThrown);
		            alert("error :: " + textStatus + " : " + errorThrown);
		        }
		    });
}
function setinputfield(feildId,value){
	$('input[id*="'+feildId+'"]').val(value);
}
</script>
		<div id="main-container">
		
			<div id="header" class="container">
				<?php echo $this->element('menu/top_menu'); ?>
			</div><!-- #header .container -->

			<div id="content" class="container <?php if (isset($this->print)) echo " width100"; ?>">

			<?php echo $this->Session->flash('flash',array('element'=>'flash/default')); ?>

				<?php echo $this->fetch('content'); ?>
			</div><!-- #header .container -->
			
<?php echo $this->element('footer')?>