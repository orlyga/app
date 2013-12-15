<div class='email_body' style="direction:rtl;font-family:arial;line-height:28px">

<?php echo __('Hello Parents of %s from %s',$this->request->data['Contact']['name'],$this->request->data['Contact']['group_name'])?>
<br/>
<?php
	$url = Router::url('/activate_member/'.	$this->request->data['Contact']['activation_key'], true);
	
?>
<hr>
<p >קרה לכם שרציתם להתקשר להורה, ורשימת הקשר שוב נעלמה? :-)<br />
 Be-member נותן פתרון פשוט וקל:</p>
קבלו לינק לדף הקשר הכיתתי: <strong>זמין תמיד, ניתן להדפסה, מציג תמונת כל ילד, ומאפשר חיוג מהיר מהנייד!</strong><br />
הרשימה פתוחה רק לחברי הקבוצה, לאחר הרשמה לצורך זיהוי ראשוני.<br />
לפתיחת דף הקשר לחצו על הלינק הבא:&nbsp; &nbsp;</p>
<?php echo  $url;?>
<hr>


<?php if(isset($this->request->data['Contact']['prefix_text'])) echo '<p>'.$this->request->data['Contact']['prefix_text'].'</p>';?><br/>

</div>