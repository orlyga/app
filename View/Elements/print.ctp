<script type="text/javascript">
function setprintresult(json1){
	
	if (json1.type=="error")
		$("#printerror").html(json1.message);
	else
	{
		$("#dialog").html("");
		$("#printerror").html("");
		var w=parseInt($("#main").css("width"));
		$( "#dialog" ).dialog({ width: w });
		w=w*4/3;
		$( "#dialog" ).dialog({ height: w });
		$( "#dialog" ).dialog({ title: "<?php echo __("Print Invitation") ?>)" });
		$("#dialog").html(json1);
		 $( "#dialog" ).dialog( "open" );
            return false;

	}
}
function setpas(user,pass){

txt_str= "עבור : <אוטומטי:שם ילד>\n"+
"הורים יקרים,\n"+
"השנה נהיה בקשר שוטף דרך אתר \"קשורים\".\n"+
"האתר ישמש אותנו להעברת הודעות, עדכון פרטי קשר ועוד...\n"+

"הכנסו לאתר : www.kshurim.com\n\r"+ 
"אנא הוסיפו לדף הקשר את הפרטים שלכם. \n"+
"או בידקו האם הפרטים נכונים, במידה ולא, אנא תקנו אותם\n"+


"הקוד שלנו: "+user+"\n"+
"הסיסמא שלנו: "+pass+"\r\n";
document.forms['print_add'].elements["add_content"].value = txt_str;
}
</script>
<div id="printerror" class="error-message"></div>
<div class="printing form">
	<?php 
 echo $this->Form->create('Print');?>
		<fieldset>
		<?php
		
			echo $this->Form->input('amount');
			echo $this->Form->input('username', array('value' => '','type'=>'hidden'));
			echo $this->Form->input('paswrd', array('value' => '','type'=>'hidden'));
			$text="עבור : <אוטומטי:שם ילד>
אנא הוסיפו לדף הקשר את הפרטים שלכם. 
או בידקו האם הפרטים נכונים, במידה ולא, אנא תקנו אותם
הכנסו לאתר: www.notesgroup.com 


הקוד שלנו:   ___________ 
הסיסמא שלנו: ___________";
	
		echo $this->Form->input('content', array('type'=>'textarea','value' => $text));
		?>
		</fieldset>
		<?php
		 echo  $this->Js->submit(__('Print',true),
		 array('before'=>$this->Js->get('#sending')->effect('fadeIn'),
	  'url'=> array('controller'=>'prints',
	   'action'=>'view'),
	    'class' => 'ajax-link button-elegant',
	    'buffer' => false ,
	    'success' => 'setprintresult(data)'
	      ));

	 echo $this->Form->end();?>




	

		<div  align="left"><button type="submit" class="button blue glossy xs" id="sub" >צפייה לפני הדפסה</button></div>
	
</div>


