<script>
<?php if(isset($active)) echo 
'$( ".selector" ).accordion( "option", "active", '.$active.' );';

?>
$(function() {
 	 $( "#accordion" ).accordion({
            heightStyle:"auto",
            event: "mouseup",
            active:false,
            icons:false,
             });   
             $(".ui-accordion-content").css("height","auto");
 });
function validate_form(){
	var val = $("#ImportImportFile").val();
if (!val.match(/(?:.doc|.docx|.xls|.xlt|.xla|.xlsx)$/)) {
    // inputted file path is not an image of one of the above types
    alert("<?php echo __('The file is not a Word File  or Excell File')?>");
    return false;
}
var val = $("#ImportName").val();
if ((val==null)||(val=="")){
	    alert("<?php echo __('Please insert your name')?>");
	    return false;
}
return true;
}


</script>



</div>
<?php $error = isset($error) ? $error : "";?>
<div id="accordionnobg" >
	<div id="accordion" class="nobg">
			 <h5><label for="t1" ><input id="t1" type="radio" name="invitetype" value="fromfile"  /> <?php echo __("From File")?></label></h5>
					<div id="inviteByfile" class='nicebox' >
						<?php echo $this->Form->create('Import',array('type' => 'file','onSubmit'=>'if(!validate_form()) return false'));?>
						<?php	echo $this->Form->input('name', array('label' => __('Invitaion from'), ));?>
						<?php	echo $this->Form->input('import_file', array('label' => __('Upload File'), 'type' => 'file',));?>
						<?php echo $this->Form->end(__('Submit',true),
	  array( 
	    'class' => 'ajax-link button-elegant',
	    
	    ));
echo $this->Form->end();?>
					<div>
						<div class='error-message'><?php echo $error?></div>
						<p class="instructions">ניתן להעלות קבצי word וexcell.<br/>
							יש לוודא שבכל שורה יהיה לפחות אי מייל או טלפון אחד
						</p>
						
					</div>
					</div>
			 <h5><label for="t2"><input id="t2" type="radio" name="invitetype" value="invite"  /> <?php echo __('Invite by Email or SMS')?></label></h5>
					 <div id="inviteByemail" class='nicebox'>
					 <?php $subject= __('Invitation to join group:').$group['Group']['name'] ;
					 $username=str_replace('@gmail.com',"",$group['User']['username']);
					  $body="<table><tr><td><h3>בונים דף קשר</h3>";
					 $body.="הנכם מוזמנים להצטרף לקבוצת <b>".$group['Group']['name']."</b><br/>";
					 $body.="העזרו בקישור הבא: "."<br/><b>http://localhost/kshurim/add/".$username."</b><br/>";
					 //$body .=__('User Name').": <b>".$username."@gmail.com</b><br/>";
					// $body .=__('Password').": <b>".$username."</b><br/></td>";
					 $body .="</td><td><img src=img/register.jpg></td></tr></table>";
					echo $body;
					 $bodyemail=str_replace('<b>',"",$body);
					  $bodyemail=str_replace('</b>','',$bodyemail);
					 $bodyemail=str_replace('<br/>','%0D',$bodyemail);
					 ?>
					<a href="#" onclick="window.location='sms:?body=<?php echo $bodyemail?>'";return false>Click Here to SMS</a>

					 	<a href="mailto:xx@yy.com?body=<?php echo $bodyemail?>&subject=<?php echo $subject?>"><?php echo __("Send Email")?></a>
						 	
							<?php	//	echo $this->Form->input('doc', array('label' => __('Upload'), 'type' => 'file',));?>
					</div>
			 <h5><label for="t3"><input id="t3" type="radio" name="invitetype" value="print"  /><?php echo __('Print Invitations')?></label></h5>
					<div id="inviteByfile" class='nicebox' >
						<?php echo $this->requestAction(array('controller'=>'prints','action'=>'view',$body)) ?>
					</div>
	</div>
</div>
</div>
<div class="content1"></div>
<div>

