<?php echo __d('croogo', 'Hello %s', $contact['name']); ?>,
<?php if(isset($contact['prefix_text'])) echo $contact['prefix_text'];?><br/>
<?php
	$url = Router::url('/activate_member/'.	$contact['activation_key'], true);
	echo __d('croogo', 'You were invited To be a member at : %s, ', $contact['group_name']);
	echo __d('croogo', 'For joining this group, Please visit this link: %s', $url);
?>