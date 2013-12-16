<?php
    Cache::delete('element_group_info'.$id);
//if(!isset($parent)) $parent="";
echo $this->element('Contacts.form', array('contact_type'=>$contact_type,'parent'=>$parent,));
?>

