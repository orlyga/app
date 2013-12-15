<script>
$(function() {
$('.collapse-group button.btn').on('click',function(){
	var col_id=$(this).closest('.collapse-group').attr("id")+'_collapse';
	$('#'+col_id).collapse('toggle');
	});
});
function validate_form(){
	var val = $("#ImportImportFile").val();
if (!val.match(/(?:.doc|.docx|.xls|.xlt|.xla|.xlsx)$/)) {
    // inputted file path is not an image of one of the above types
    alert("<?php echo __('The file is not a Word File  or Excell File')?>");
    return false;
}
var val = $("#ImportFileName").val();
if ((val==null)||(val=="")){
	    alert("<?php echo __('Please insert your name')?>");
	    return false;
}
$("#ImportFileInviteForm").submit();
}

</script>
<div id="invite" class='span5' >
<br/>
<div style="font-size:80%">
<p>את/ה מנהל הקבוצה. ככזה, ברשותך להזמין חברים לקבוצה. כמו כן, רק לך יורשו הפעולות הבאות:</p>

<ul style="margin-right:5%">
<li>הזמנת חבר</li>
	<li>ביטול חברות</li>
	<li>עדכון פרטי חבר</li>
	<li>שינוי בפרטי הקבוצה</li>
</ul>	
</div>
<h3><?php echo __('Select one of the following ways to invite the group members')?></h3>

<?php $error = isset($error) ? $error : "";?>
	<!------------Invite by email SMS------------->
<div class="collapse-group" id="byEmail">
	<button href="#" class='btn' > <?php echo __('Invite by Email or SMS')?></button>
	<p class="instructions">
		במידה ובידכם כתובות אי מייל של חברי הקבוצה, תוכלו לשלוח אי מייל, ובו לינק עם הזמנה להצטרפות לקבוצה. 
	</p>

	<div class="collapse" id="byEmail_collapse">
		<div id="inviteByemail" class='nicebox' >
		<p class="instructions-noicon">	לחיצה על הכפתור האדום תעביר את תוכן ההזמנה אל תוך ההודעה שתכתבו
		</p>
		<?php $subject= 'Invitation to join group: '.$group['Group']['name'] ;
			 $username=str_replace('@gmail.com',"",$user['User']['username']);
						  $body="<table><tr><td><h4>הצטרפו לדף הקשר</h4>";
						 $body.="הנכם מוזמנים להצטרף לדף הקשר של  <br/><b>".$group['Group']['name']."</b><br/>";
						 $body.="העזרו בקישור הבא: "."<br/><b>www.be-member.com/add/".$username."</b><br/>";
						 $body .="</td></tr></table>";
						 ?>
						 <a href="#" class='btn btn-danger hide' onclick="window.location='sms:?body=<?php echo $bodyemail?>'";return false>Click Here to SMS</a>
							<!--<br/><br/>OR<br/><br/>-->
						 	<a class='btn btn-danger' href="mailto:xx@yy.com?body=<?php echo $bodyemail?>&subject=<?php echo $subject?>"><?php echo __("Send Email with the following content")?></a>
						
						<div class="box" style="padding:5%">
						
								<?php	echo $body;?>
						</div>
											 
						
		</div>  
	</div>
</div>
  	<!------------Invite by printing------------->
<div class="collapse-group" id="byLetter">
	<button href="#" class='btn' > <?php echo __('Hand out Invitations')?></button>
	<p class="instructions">
	הדפיסו הזמנות וחלקו באופן ידני לחברי הקבוצה
	</br>
	הדפים יודפסו ברצועות, ראו תצוגה לפני הדפסה	
	</br>
	</p>
	<div class="collapse" id="byLetter_collapse">
        <div  class='nicebox' >
		
					<?php echo $this->element('FileManager.print_slip',array('body'=>$body)) ?>
		</div>
    </div>
</div>

   <!------------Invite by file------------->
  <div class="collapse-group" id="byFile">
	<button href="#" class='btn' > <?php echo __('By File')?></button>
	<p class="instructions">
	אם ברשותכם רשימה קיימת של חברי הקבוצה, תוכלו להעלות את הקובץ כאן.
	כל חבר קבוצה יקבל בצורה אוטומאטית אי מייל הזמנה, כאשר כל פרטיו כבר מוזנים למערכת.
	</br>
	</p>
	<div class="collapse" id="byFile_collapse">			 	
        <div  class='nicebox' style="margin-right:10%">
						<?php echo $this->element("FileManager.uploadlist")?>
		</div>
    </div>
</div>	
	 	
<!--first invite--------------------------------->

<a href="<?php echo $this->html->url("/addMember")?>" class='btn'><?php echo __('Add members One by One')?></a>
<div class='collapse-group'>	
<p class="instructions">
	אין ברשותכם קובץ, אך יש לכם מידע לגבי חברי הקבוצה?
	תוכלו להזין את הפרטים עבור כל חבר.
	</br>
	</p>
</div>	
</br>
</div>
				