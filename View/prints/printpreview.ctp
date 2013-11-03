<?php
$content_for_layout= str_replace("[","<",$content_for_layout);
$content_for_layout= str_replace("]",">",$content_for_layout);
$content_for_layout= str_replace("]",">",$content_for_layout);
 $path = $this->Html->assetUrl('222', array('pathPrefix' => IMAGES_URL)); 
	$path=substr($path,0,strlen($path)-4)."/";
	$content_for_layout= str_replace("img/","$path",$content_for_layout);
	
echo $content_for_layout;

?>