<?php

// Users
CroogoRouter::routableContentTypes();
Router::parseExtensions();
CroogoRouter::connect('/', array(
	'plugin' => 'groups', 'controller' => 'groupsusers', 'action' => 'GroupsUserList'
));
CroogoRouter::connect(  '/robots.txt',   array(  
        'controller' => 'seo',  
        'action' => 'robots'  
    )  
); 
CroogoRouter::connect(  '/pic/*',   array(  
        'plugin' => 'nodes',
        'controller' => 'nodes',  
        'action' => 'view',
        'slug' => 'home-page','type'=>'page'  
    )  
);   
CroogoRouter::connect('/home-page', array(
'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'view','slug' => 'home-page','type'=>'page'));
CroogoRouter::connect('/user/:username', array('plugin' => 'users', 'controller' => 'users', 'action' => 'view'), array('pass' => array('username')));
CroogoRouter::connect('/register', array('plugin' => 'users', 'controller' => 'users', 'action' => 'add'));
CroogoRouter::connect('/users/users/register', array('plugin' => 'users', 'controller' => 'users', 'action' => 'add'));
CroogoRouter::connect('/users/users/add', array('plugin' => 'users', 'controller' => 'users', 'action' => 'add'));
CroogoRouter::connect('/aregister', array('plugin' => 'users', 'controller' => 'users', 'action' => 'after_register'));
CroogoRouter::connect('/facebook_register', array('plugin' => 'users', 'controller' => 'users', 'action' => 'facebook_register'));
CroogoRouter::connect('/logout', array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout'));
CroogoRouter::connect('/facebook_login', array('plugin' => 'users', 'controller' => 'users', 'action' => 'facebook_login'));
CroogoRouter::connect('/login', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));

/*Groups Groups*/
CroogoRouter::connect('/addgroup', array('plugin' => 'groups', 'controller' => 'groups', 'action' => 'add'));
CroogoRouter::connect('/groups/groups/add', array('plugin' => 'groups', 'controller' => 'groups', 'action' => 'add'));
CroogoRouter::connect('/invitetogroup',array('plugin' => 'groups', 'controller' => 'groups', 'action' => 'invite'));
CroogoRouter::connect('/groups/test',array('plugin' => 'groups', 'controller' => 'groups', 'action' => 'test'));
CroogoRouter::connect('/groups/groups/view/:group_id', array('plugin' => 'groups','controller' => 'groups', 'action' => 'view'),array('pass' => array('group_id')));
CroogoRouter::connect('/groups/view/:group_id', array('plugin' => 'groups','controller' => 'groups', 'action' => 'view'),array('pass' => array('group_id')));
CroogoRouter::connect('/groups/view/:group_id', array('plugin' => 'groups','controller' => 'groups', 'action' => 'view'),array('pass' => array('group_id')));
CroogoRouter::connect('/groupsview', array('plugin' => 'groups','controller' => 'groups', 'action' => 'view'));
CroogoRouter::connect('/viewSwitch/:group_id_session', array('plugin' => 'groups','controller' => 'groups', 'action' => 'view'),array('pass' => array('group_id_session')));
CroogoRouter::connect('/viewgroup', array('plugin' => 'groups','controller' => 'groups', 'action' => 'view_nouser'));
CroogoRouter::connect('/view/:group_id', array('plugin' => 'groups','controller' => 'groups', 'action' => 'switchGroup'),array('pass' => array('group_id')));
CroogoRouter::connect('/groups/groups/edit/:group_id', array('plugin' => 'groups','controller' => 'groups', 'action' => 'edit'),array('pass' => array('group_id')));
CroogoRouter::connect('/groups/groups/delete/:id', array('plugin' => 'groups','controller' => 'groups', 'action' => 'delete'),array('pass' => array('id')));

/*Groups GroupsUsers*/
CroogoRouter::connect('/add/:tempuser', array('plugin' => 'groups','controller' => 'members', 'action' => 'add_member_from_tmp_user'),array('pass' => array('tempuser')));
CroogoRouter::connect('/add_member_from_tmp_user', array('plugin' => 'groups','controller' => 'members', 'action' => 'add_member_from_tmp_user'),array('pass' => array('tempuser')));
CroogoRouter::connect('/groups/groupsusers/GroupsUserList', array('plugin' => 'groups','controller' => 'groupsusers', 'action' => 'GroupsUserList'));
CroogoRouter::connect('/add_member_admin', array('plugin' => 'groups','controller' => 'members', 'action' => 'add_member_admin'));
CroogoRouter::connect('/groupslist', array('plugin' => 'groups','controller' => 'groupsusers', 'action' => 'GroupsUserList'));


 CroogoRouter::connect('/addMember',array('plugin' => 'groups','controller' => 'members', 'action' => 'add_member_by_group_admin'));
 CroogoRouter::connect('/addStaff',array('plugin' => 'groups','controller' => 'members', 'action' => 'add_member_by_group_admin','staff')); 
 CroogoRouter::connect('/addstaffMember',array('plugin' => 'groups','controller' => 'members', 'action' => 'add_member_by_group_admin',"staff"));
 CroogoRouter::connect('/addStaff/:type',array('plugin' => 'groups','controller' => 'members', 'action' => 'add_member_by_group_admin'),array('pass'=>array('type')));

 /*Groups Members*/
CroogoRouter::connect('/groups/members/delete/:member_id', array('plugin' => 'groups','controller' => 'members', 'action' => 'delete'),array('pass' => array('member_id')));
CroogoRouter::connect('/activate_member/:activation_key', array('plugin' => 'groups','controller' => 'members', 'action' => 'activate_member'),array('pass' => array('activation_key')));
CroogoRouter::connect('/activate_member/:activation_key/*', array('plugin' => 'groups','controller' => 'members', 'action' => 'activate_member'),array('pass' => array('activation_key')));
CroogoRouter::connect('/resendInvite/:member_id', array('plugin' => 'groups','controller' => 'members', 'action' => 'resend_member_invite_email'),array('pass' => array('member_id')));
CroogoRouter::connect('/approveMember', array('plugin' => 'groups','controller' => 'members', 'action' => 'member_group_approval'));
CroogoRouter::connect('/updateMember/:member_id', array('plugin' => 'groups','controller' => 'members', 'action' => 'edit_member'),array('pass' => array('member_id')));

/*File Manager*/
CroogoRouter::connect('/printpreview',array('plugin'=>'file_manager','controller'=>'printouts','action'=>'printpreview'));
CroogoRouter::connect('/printgroup',array('plugin'=>'file_manager','controller'=>'printouts','action'=>'printgroup'));
CroogoRouter::connect('/importgroup',array('plugin'=>'file_manager','controller'=>'imports','action'=>'importgroup'));

/*Contact*/
CroogoRouter::connect('/getContact/:email', array('plugin' => 'contacts','controller' => 'contacts', 'action' => 'checkContactExist'),array('pass' => array('email')));
CroogoRouter::connect('/getContactChildren/:email', array('plugin' => 'contacts','controller' => 'contacts', 'action' => 'getContactChildren'),array('pass' => array('email')));

