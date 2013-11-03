<script>
    function set_logged_user(user_name) {
        $("#user-logged-name").html(user_name);
        $("#div-not-login").addClass('hide');
        $("#div-is-logged").removeClass('hide');
    }

   var animateMe = function(targetElement, speed){
    $(targetElement).css({'width':'20px','margin-left':'-10px','margin-top':'-10px'});
     $(targetElement).animate(
        {
        'width': '40px','margin-left':'-20px','margin-top':'-20px'
            }, 
        { 
        duration: speed, 
        easing:'swing',
       
        complete: function(){
            animateMe2(this, speed);
            }
        }
    );
    
};
var animateMe2 = function(targetElement, speed){
    $(targetElement).css({'width': '40px','margin-left':'-20px','margin-top':'-20px'});
     $(targetElement).animate(
        {
          'width':'20px','margin-left':'-10px','margin-top':'-10px'
            }, 
        { 
        duration: speed, 
        easing:'swing',
       
        complete: function(){
            animateMe(this, speed);
            }
        }
    );
    
};
    function is_login_needed() {
        return $("#div-is-logged").hasClass('hide');
    }
    $(function () {
        var bIsMobile = (navigator.userAgent.toLowerCase().indexOf("mobile") != -1 && navigator.userAgent.toLowerCase().indexOf("ipad") == -1);
        if (bIsMobile) {
            var ua = navigator.userAgent.toLowerCase();
            var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
            var isChrome = ua.indexOf("chrome") > -1; //&& ua.indexOf("mobile");
            if (isAndroid || isChrome) {
                $("#bookmark_chrom").removeClass("hide");
                animateMe($("#bookmark_chrom_a"),2000); }
        }
    })
</script>
<?php if(!$loggeduser) $hide= 'hide'; else $hide="";
$show_list=($showList)?"":"hide";?>

<nav>
		<a href="<?php echo $this->html->url("/")?>"><?php echo $this->Html->image('logo.png',array('class'=>'logo')); ?></a>
</a>
<div id='menu-mygroups' style='display: inline-block'>
		
			 		<a style="padding:0;margin:0 " href='<?php echo $this->html->url("/groupslist") ?>'><?php echo __('My Groups')?></a>
			 		
		
</div>
		<a id="revealMenu" class="collapse-button" data-toggle="collapse" data-target=".nav-collapse">
		 
		</a>
		<?php if (isset($this->back_button)){ ?>
	 	
        <?php }?>
	<div class='collapse-wrapper'>
		<div class="nav-collapse" style="min-height:100px;display:inline-block;position:absolute">
			<ul class="block show">
				<?php echo $this->element('menu/menu_items');?>
				<li></li>
			</ul>
		</div>
	</div>
	 
        
	</div>
</nav>

<div class="nav" style='display:none'>
	<div class="navbar-inner">
		<div class="container" style="padding-left:2%;padding-right:2%">
         <?php if (isset($this->back_button)){ ?>
        <div class="arrowbtn pull-left" style="margin-left: -2%">
            <?php echo $this->back_button;?>
        </div>
        <?php }?>
           
		<a  class="pull-left" href="<?php echo $this->html->url("/")?>"><?php echo $this->Html->image('logo.png',array('class'=>'logo')); ?></a>
       
         <a style="margin-right: -5%"class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</a>
		
<?php  if (!empty($loggeduser)) {?>
				 <div id='user-logged-name' class=" text-bold-light "><?php echo __("Hi,")." ".  $loggeduser; ?></div>
<?php }?>
</div>
     
    </div>

</div>
