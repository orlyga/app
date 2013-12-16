
<div><div id="dddchildren"><?php if( count($contacts)> 0) {?>
   <h4><?php echo __('Children of'). " ". $parent['Contact']['name']. " ". $parent['Contact']['last'] ?></h4>

<ul id= "children-list" class="list"  >
<?php foreach ($contacts as $contact){
echo '<li id="'.$contact['Contact']['id'].'"><h3><a href="#">
    <input name="child" type="radio">'.$contact['Contact']['name'].'</a></h3></li>';
  
}
    echo '</ul></div>';
}

 if( count($secondparents)> 0) {
?>
<div id="dddsecond">
    <label><?php echo __("Choose related parent:")?></label>
            <select id="selectsecondparent" onchange="select_second_parent(this.value)">
 <option><?php echo __('Select Second parent')?></option>
            
<?php
     foreach ($secondparents as $secondparent){

    echo '<option ';
    echo ' value='.$secondparent['Contact']['id'].' >';
    echo  $secondparent['Contact']['name']." ".$secondparent['Contact']['last'];
    echo '</option>';

     }
    echo '</select></div>';
}
?>
               
<div id='parent_id' style='display:none'><?php echo $parent['Contact']['id']?></div>
<div id='parent_last' style='display:none'><?php echo $parent['Contact']['last']?></div>
<div id='parent_name' style='display:none'><?php echo $parent['Contact']['name']?></div>
<div id="firstparentlist">
       
    </div>
 </div>
