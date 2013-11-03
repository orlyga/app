<?php
if(!isset($parent)) $parent="";
echo $this->element('Contacts.form', array('contact_type'=>$contact_type,'parent'=>$parent,));
?>
