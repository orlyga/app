<div>
<?php

echo '<h3 style="font-size:120%">'.__('Upload summery').'</h3>';

echo '<b>'.__("Total of rows").': </b>'.$total_row;
echo '<hr>';
echo '<b>'.__("Total of added members").': </b>'.$good_rows;
if ($text_err<>""){
echo '<h3 style="font-size:120%;color:red">'.__('Problems found in the list').'</h3>';
	echo $text_err;}
echo '<hr>';
echo $this->html->link(__('View Group'),'/groupsview',array('class'=>'btn green_gradient'));
?>
</div>
<a href=>