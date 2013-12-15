<?php

$origfieldset = array(
'name'=> array('class'=>'contact name'),
'last'=> array('class'=>'contact last'),
'address'=>array('class'=>'contact address'),
'city_id'=>array('class'=>'contact address city'),

'phone'=>array('class'=>'contact address'),
'cellphone'=>array('class'=>'contact address'),
'email'=>array('class'=>'contact email'),
'image'=>array('class'=>'contact photo'),
'birth_date'=>array('class'=>'contact birthdate'),
'gender'=>array('class'=>'contact gender'),
'contact_comments'=>array('class'=>'hide'),
);
if (isset($fieldset)){
	foreach ($fieldset as $i => $key) {
		$origfieldset[$i] = array_merge($origfieldset[$i],$key);
	}
}

if(!function_exists("set_field")) {
	function set_field($fld,$origfieldset,$contact){
		if((!empty($contact[$fld])) && ($origfieldset[$fld]['class']<>"hide"))
		{
			return "<span class='{$origfieldset[$fld]['class']}'>{$contact[$fld]}</span>";
		}else return "";
	}
	
}	
if(!function_exists("format_phone"))	{function format_phone($phone,$type)
{
	$phone = preg_replace("/[^0-9]/", "", $phone);
	if($type=="cell")
		return preg_replace("/([0-9]{3})([0-9]{7})/", "$1-$2", $phone);
		//	return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
	if($type=="phone"){
		if (strlen($phone)==10)
			return preg_replace("/([0-9]{3})([0-9]{7})/", "$1-$2", $phone);
		elseif (strlen($phone)==9)
			return preg_replace("/([0-9]{2})([0-9]{7})/", "$1-$2", $phone);
	}
	else
		return $phone;
}
}

?>
<div class='fullname'>
	<?php
	echo set_field('name',$origfieldset,$contact);
	echo set_field('last',$origfieldset,$contact);
	?>
</div>
<?php

//echo set_field('gender',$origfieldset,$contact);
if(isset($contact['birth_date'] )){
$contact['birth_date']= date('d-m-Y', strtotime($contact['birth_date']));}
echo set_field('birth_date',$origfieldset,$contact);
$phone= set_field('cellphone',$origfieldset,$contact);
if ($phone<>"") {
	$phone=format_phone($contact['cellphone'],'cell');
	echo "<span class='phone'><i class='icon-mobile-phone'></i><a class='phone' href='tel:{$contact['cellphone']}'>{$phone}</a></span>";
}
$phone= set_field('phone',$origfieldset,$contact);
if ($phone<>"") {
	$phone=format_phone($contact['phone'],'phone');
	echo "<span class='phone'><i class='icon-phone'></i><a class='phone' href='tel:{$contact['phone']}'>{$phone}</a></span>";
}

$email= set_field('email',$origfieldset,$contact);
if ($email<>"") echo "<a class='email' href='mailto:{$contact['email']}'><i class='icon-envelope'></i>{$contact['email']}</a>";
echo set_field('contact_comments',$origfieldset,$contact);
$address="";
$contact['address']=$contact['address']. " ". $contact['city_id'];
	$address = set_field('address',$origfieldset,$contact);
//	$address .= set_field('city_id',$origfieldset,$contact);
	if ($address<>""){
	?>
		<div class='fulladdress' ><icon class="icon-home"></icon>
		<a href='waze://?q=<?php echo $contact['address']; ?>'><?php echo $address; ?></a>
		</div>
		<?php }  ?>


