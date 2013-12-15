<?php

echo $this->Facebook->registration(array('loggedin'=>$fb_user,'redirect-uri' => 'http://be-member.com/facebook_register',));

?>