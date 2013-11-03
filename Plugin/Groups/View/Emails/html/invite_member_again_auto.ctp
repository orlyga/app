<div class='email_body' style="direction:rtl;font-family:arial;line-height:28px">

<?php echo __('Hello Parents of %s from %s',$this->request->data['Contact']['name'],$this->request->data['Contact']['group_name'])?>
<br/>
<?php
	$url = Router::url('/activate_member/'.	$this->request->data['Contact']['activation_key'], true);
	
?>
<hr>
<p >
טרם הצטרפתם לדף הקשר <br />
מומלץ להוסיף קישור לדף הקשר. <br />
לפתיחת דף הקשר לחצו על הלינק הבא:&nbsp; &nbsp;</p>
<?php echo  $url;?>
<hr>


<?php if(isset($this->request->data['Contact']['prefix_text'])) echo '<p>'.$this->request->data['Contact']['prefix_text'].'</p>';?><br/>

</div>