<?php
/*Element input:
parent: prefix of field without the 'Contact'
 * index:to be added to prefix after 'Contact' for multi contacts
 * $fieldset: customized options for fields
 * contact_type: pre-formatted contacts
*/
?>
<script>
    function afterContactMatched(data){
                        
    		            $("#recieved-matched").html($(data).find("#recieved-matched").html());
    //need to set the found contatctid
   
}

</script>
<?php

//echo $this->Html->script('autocomplete');
 //echo $this->Html->script('fileuploader');
if (!isset($cities))
	$cities=$this->requestAction(array('plugin'=>'contacts','controller'=>"contacts",'action'=>'setCities'));
	$field_prefix = "";
if (!isset($email)){
	$email=array();

}

$contactprefix=($contact_type=='parent')? "":'Contact.';
if (isset($index)&&($index<>"")){
	$field_prefix = $contactprefix.$index.'.';

}
else {
	$index="";
	$field_prefix = $contactprefix;
}
if (isset($parent)) {
	if($parent<>"") $parent=$parent.".";
	if(isset($parent_index))
		$field_prefix = $parent.$parent_index.".".$field_prefix;
	else {
		
		$field_prefix = $parent.$field_prefix;
	}
}
else 	$parent="";
$field_prefix_for_jquery=str_replace(".","",$field_prefix);
$target_image_div="#".$field_prefix_for_jquery."Image";
if (!isset($hostingdiv))
	$hostingdiv="null";
else
	$target_image_div="#".$hostingdiv." ".$target_image_div;


$image_par="";
$index_img=(isset($index))?$index:(isset($parent_index))?$parent_index:null;
if (isset($this->request->data)&&(isset($image))){
	$image_par=(is_array($image))?(!empty($index_img))?$image[$index_img]:"":$image;
	//pr($image[$index]);
		//	 $image_par=(isset($index))?$image[$index]:$image;

}

?>
 
<script type="text/javascript">
  $(function() {
 // 	$("#ContactCityId").typeahead({ "source"<?php echo serialize($cities)?>});
  	<?php if($image_par<>"")
	             echo  'set_initial_img("'.$image_par.'","'.$hostingdiv.'");';    
	             ?>
var mode=$("#<?php echo $field_prefix_for_jquery."ContactMode"?>").val();
if (mode=='readonly'){
	$("#<?php echo $hostingdiv?> input").attr("readonly",true);
	$("#<?php echo $hostingdiv?> textarea").attr("readonly",true);
	$("#<?php echo $hostingdiv?> select").attr("readonly",true);
	$("#<?php echo $hostingdiv?> file").attr("readonly",true);
		//$(".qq-upload-button").css("background",'gray');

	
}
	
          
           });
 </script>

<?php 
$default_city= (isset($default_city)) ?$default_city:null;
$genderoptions=array(1=>__("Boy"),
	       	2=>__('Girl'),);

$origfieldset = array(
'name'=> array('required'=>'required',),
'id'=> array("type"=>"hidden"),
'last'=> array('title'=>__('Last Name',true)),
'address'=>array('type'=>'textarea','rows'=>1,'cols'=>16,'title'=>__('House No. and Street',true)),
'city_id'=>array('type'=>'select',"empty"=>__("Select")." ".__("City",true),'label'=>false,'options'=>$cities),
'postcode'=>array("type"=>"hidden"),
'phone'=>array('type'=>'tel'),
'cellphone'=>array('type'=>'tel','placeholder'=>__("Cellphone"),'label'=>false),
'email'=>array('type'=>'email','placeholder'=>__("Email"),'label'=>false),
'image'=>array("type"=>"file",),
'parent_id'=>array("type"=>"hidden",),
'birth_date'=>array('type'=>'date','label'=>__("Date of Birth"),'dateFormat'=>__('DMY'),'minYear'=>date('Y')-18,'maxYear'=>date('Y'), 'monthNames' => false,'empty' => array('day'=>__('Day'),'month'=>__('Month'),'year'=>__('Year'))),
'gender'=>array('legend'=>false,'div'=>'false','type'=>'radio','options' => $genderoptions),
'contact_comments'=>array(),
);
//,"pattern"=>$this->CroogoForm->getFieldRegex('Contact',"cellphone",__('il'))
if(!isset($contact_type)) $contact_type='adult';
if(!isset($fieldset)){
	switch ($contact_type){
	case 'adult':
    case 'group':
    $fieldset=array(
    'email'=>array('type'=>'hidden','secure'=>'false'),
    'name'=>array('type'=>'hidden','secure'=>'false'),
    'cellphone'=>array('type'=>'hidden','required'=>'false'),
    'last'=>array('type'=>'hidden','value'=>'Group'),
    'gender'=>array('type'=>'hidden'),
    'birth_date'=>array('type'=>'hidden'),
    'image'=>array('type'=>'hidden'),
    'city_id'=>array('required'=>'required'),);
    break;
	case 'parent-nouser':
    	$fieldset=array(
		'image'=>array('type'=>'hidden'),
		'city_id'=>array('type'=>'hidden','value'=>$default_city),
		'address'=>array('type'=>'hidden'),
		'phone'=>array('type'=>'hidden'),
		'gender'=>array('type'=>'hidden'),
		'birth_date'=>array('type'=>'hidden'),
		'name'=>array('required'=>'required','placeholder'=>__("Parent Name"),'label'=>false));
	    break;
    case 'parent':
    	$fieldset=array(
		'image'=>array('type'=>'hidden'),
        'gender'=>array('type'=>'hidden'),
		'birth_date'=>array('type'=>'hidden'),
		'phone'=>array('type'=>'hidden'),
		'name'=>array('placeholder'=>__("Parent Name"),'label'=>false));
	    break;
	case 'staff':
		$fieldset=array(
		'address'=>array('type'=>'hidden','secure'=>'false'),
		'image'=>array('type'=>'hidden'),
        'gender'=>array('type'=>'hidden'),
		'birth_date'=>array('type'=>'hidden'),
		'city_id'=>array('type'=>'hidden','value'=>$default_city),
		'postcode'=>array('type'=>'hidden'),
		'phone'=>array('type'=>'hidden'),
		'cellphone'=>array('required'=>false),
		'email'=>array('required'=>false));
		break;
	case 'head-staff':
	$fieldset=array(
		'gender'=>array('type'=>'hidden'),
		'birth_date'=>array('type'=>'hidden'),
		'address'=>array('type'=>'hidden','secure'=>'false'),
		'image'=>array('type'=>'hidden'),
		'postcode'=>array('type'=>'hidden'),
		'cellphone'=>array('required'=>false),
		'phone'=>array('label'=>false,'placeholder'=>__("Home Phone")),
		'city_id'=>array('type'=>'hidden','value'=>$default_city),
		'contact_comments'=>array('type'=>'textarea','label'=>false,'placeholder'=>__("Contact Comments")),
		'name'=>array());
		break;
	case 'child-member':
			// echo $this->Form->input('Family.group_id',array('type' => 'hidden'));
			$fieldset=array(
				'address'=>array('label'=>false,'rows'=>2,'cols'=>17,'placeholder'=>__("Home Address")),
                'cellphone'=>array('type'=>'hidden','required'=>false),
                'email'=>array('type'=>'hidden','required'=>false),
                 'name'=>array('placeholder'=>__('Child Name'),'label'=>false),
				'phone'=>array('label'=>false,'placeholder'=>__("Home Phone")));
	break;
	case 'child-member-no-parent':
		$fieldset=array(
				'address'=>array('rows'=>2,'cols'=>17),
                'cellphone'=>array('type'=>'hidden'),
                'city_id'=>array('value'=>$default_city),
                 'last'=>array('type'=>'hidden'),
                 'name'=>array('placeholder'=>__('Child Name'),'label'=>false),
                'email'=>array('type'=>'hidden'));
	break;
	default:
	$fieldset=array(
	  			'gender'=>array('type'=>'hidden'),
				'birth_date'=>array('type'=>'hidden'),
				'image'=>array('type'=>'display'));
				break;
				
	}
}

foreach ($fieldset as $i => $key) {
		if (isset($key['type'])&& ($key['type']=='hidden')) $origfieldset[$i]=array();
		if (isset($key['type'])&& ($key['type']=='display')) unset($origfieldset[$i]['type']);
		$origfieldset[$i] = array_merge($origfieldset[$i],$key);
	}



echo $this->Form->input($field_prefix.'id',$origfieldset['id']);
echo $this->CroogoForm->input($field_prefix.'name',$origfieldset['name']);
echo $this->CroogoForm->input($field_prefix.'last',$origfieldset['last']);
echo $this->CroogoForm->input($field_prefix.'cellphone',$origfieldset['cellphone']);
echo '<div>'.$this->CroogoForm->input($field_prefix.'email',$origfieldset['email']).'</div>';
echo '<div id="recieved-matched">';
echo $this->CroogoForm->input($field_prefix.'gender',$origfieldset['gender']);
echo $this->CroogoForm->input($field_prefix.'birth_date',$origfieldset['birth_date']);
echo $this->CroogoForm->input($field_prefix.'city_id',$origfieldset['city_id']);
echo $this->CroogoForm->input($field_prefix.'address',$origfieldset['address']);
echo '</div>';
echo $this->Form->input($field_prefix.'contact_mode',array('type'=>'hidden'));


	//	echo $this->Form->input('state');
	//	echo $this->Form->input('country');
echo $this->CroogoForm->input($field_prefix.'postcode',$origfieldset['postcode']);
echo $this->CroogoForm->input($field_prefix.'phone',$origfieldset['phone']);
if (isset ($origfieldset['contact_comments']['placeholder']))
	echo $this->CroogoForm->input($field_prefix.'contact_comments',$origfieldset['contact_comments']);
if ($origfieldset['image']['type']<>'hidden') {
         if (!empty($this->data['Contact']['image'])): ?>
	        <div class="input">
		        <label><?php echo __('Replace Photo')?></label>
		        <?php
		        echo $this->CroogoForm->input($field_prefix.'image',$origfieldset['image']);
		        echo $this->Html->image($this->data['Contact']['image'],array('style'=>'width:80px'));
		        ?>
	        </div>
        <?php else: ?>
	        <?php echo $this->CroogoForm->input($field_prefix.'image',$origfieldset['image']);
         ?>
        <?php endif; 
}
echo $this->CroogoForm->input($field_prefix.'parent_id',$origfieldset['parent_id']);

$this->Form->unlockField($field_prefix.'image');
$this->Form->unlockField($field_prefix.'birth_date.month');
$this->Form->unlockField($field_prefix.'birth_date.year');
$this->Form->unlockField($field_prefix.'birth_date.day');
$this->Form->unlockField($field_prefix.'contact_id');
$this->Form->unlockField($field_prefix.'contact_mode');
$this->Form->unlockField($field_prefix.'parent_id');


$this->Form->unlockField($field_prefix.'city_id');


	//		echo $this->Form->input($field_prefix.'id');
		//	echo $this->Form->input($field_prefix.'request_status');
			
?>

