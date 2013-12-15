<?php

App::uses('UsersAppModel', 'Users.Model');

/**
 * RolesUser
 *
 *
 * @category Model
 * @package  Croogo.Users.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 * functions:
 isContactInGroup
 getUserRoleInGroup
 getUserByMemberType
 getUserByUserRole
 getUserGroupByGroupandRole
 getGroupsByUser
 getGroupsByUsername
 getContactsByuser
 getGroupsByUsername
 getContactsByrelationUser
 check_duplicate_member
 get_members_id_by_status
 get_members_by_group
 deleteByMemeber
 activateMember
 setGU
 */
class GroupsUser extends GroupsAppModel {
    public $actsAs = array(
			'Search.Searchable',
			'Croogo.Cached' => array(
			'groups' => array(
				'groups',
			),
            ),

	);
	
	public $belongsTo = array(
			'Group' => array('className' => 'Groups.Group'),
			'User' => array('className' => 'Users.User'),
			'Member' => array('className' => 'Groups.Member')
	);

/**
 * Get Ids of Role's Aro assigned to user
 *
 * @param $userId integer user id
 * @return array array of Role Aro Ids
 */
public function beforeSave($options){
  
          if(isset($this->data['GroupsUser']['user_id'])&&
    isset($this->data['GroupsUser']['group_id'])&&
    isset($this->data['GroupsUser']['member_id'])){
            $gu=$this->find('first',array('conditions'=>array(
            'GroupsUser.user_id'=>$this->data['GroupsUser']['user_id'],
            'GroupsUser.group_id'=>$this->data['GroupsUser']['group_id'],
            'GroupsUser.member_id'=>$this->data['GroupsUser']['member_id'],
            )));
            
            if ($gu) { $this->id=$this->data['GroupsUser']['id']=$gu['GroupsUser']['id'];return false;} //no need to save
    }
	parent::beforeSave($options);
    }
public function afterSave($create){
         App::import( 'CakeSession');

        $group=CakeSession::check('Group.Group');
         if( $group){

			$group=CakeSession::read('Group.Group');
            //foreach role of group need to makey it dynamic foreach role in the role table
            Cache::delete('element_mem_list1_4_'.$group,'groups_view');

    		Cache::delete('element_mem_list2_4_'.$group,'groups_view');
             Cache::delete('element_mem_list1_5_'.$group,'groups_view');

    		Cache::delete('element_mem_list2_5_'.$group,'groups_view');
             Cache::delete('element_mem_list1_6_'.$group,'groups_view');

    		Cache::delete('element_mem_list2_6_'.$group,'groups_view');

         }
     
	return $this->User->afterUserwasAddedToGroup($this->Group->id);
}
public function isContactInGroup($group_id,$contact_id){
    $sql='SELECT GroupsUser.*
FROM groups_users GroupsUser
INNER JOIN members m ON m.id = GroupsUser.member_id
WHERE m.contact_id ='.$contact_id .' and GroupsUser.group_id='.$group_id;
 $return=$this->query($sql);
 
   return $return[0];
    
   
}
public function getUserRoleInGroup($user_id,$group_id){
	$this->recursive=0;
	
	$return=$this->find('first',array(
			'conditions'=>array('GroupsUser.group_id'=>$group_id,'GroupsUser.user_id'=>$user_id),
			'fields'=>array('GroupsUser.role_id'),
            'cache' => array(
				'name' => 'roleingroup'.$user_id.$group_id,
				'config' => 'croogo_groups',)
			));
          
	if ($return)
		return $return['GroupsUser']['role_id'];
	else return false;
}
public function getUserByMemberType($group_id,$member_type,$limit=100){
		$this->recursive=0;
		$return=$this->find('all',array(
				'conditions'=>array('GroupsUser.group_id'=>$group_id,'Member.member_type'=>$member_type),
				'order'=>array('GroupsUser.created'=>'Asc'),
				'fields'=>array('User.*'),
				'limit'=>$limit));
		return $return;
	}
public function getUserByUserRole($group_id,$role_id=5,$limit=1){
		$this->recursive=0;
		$all= ($limit==1) ? 'first':'all';
		$return=$this->find($all,array(
				'conditions'=>array('GroupsUser.group_id'=>$group_id,'User.role_id'=>$role_id),
				'order'=>array('GroupsUser.created'=>'Asc'),
				'limit'=>$limit,
				'fields'=>"User.*"));
		return $return;
	}
public function getUserGroupByGroupandRole($group_id,$role_id=5,$limit=1,$recursive=-1,$fields="GroupsUser.*"){
		$this->recursive=$recursive;
		$all= ($limit==1) ? 'first':'all';
		$return=$this->find($all,array(
				'conditions'=>array('GroupsUser.group_id'=>$group_id,'GroupsUser.role_id'=>$role_id),
				'order'=>array('GroupsUser.created'=>'Desc'),
				'limit'=>$limit,
				'fields'=>$fields));
		return $return;
	}
    function getGUByUserContactIDGroup($contact_id,$group_id=null){
	$select=($group_id==null)? "": " and GU.group_id=".$group_id;
	
	
	$query=	"select GU.*,User.id from groups_users as GU
			right join users as User on GU.user_id=User.id 
			where User.contact_id=".$contact_id.$select;
	return $this->query($query);
}
public function getGroupsByUser($user_id) {
	$this->recursive=0;
		$groups = $this->find('all',array(
				'conditions' => array('GroupsUser.user_id = '.$user_id),
				'fields'=>array('GroupsUser.group_id','Group.name'),
				'group'=>array('GroupsUser.group_id'),
				 'cache' => array(
				'name' => 'usersgroups'.$user_id,
				'config' => 'croogo_groups',
			),
		));
		return $groups;
	}
public function getGroupsByUsername($user_name) {
		$groups = $this->find('first',array(
				'conditions' => array('User.username'=>$user_name),
				'fields'=>array('GroupsUser.group_id','Group.name'),
				'recursion'=>0
		));
		return $groups;
	}
	//return array with setup data
	//or return false in case of user not found
	//or returns array $return['error'] string and array with error message
	//$username = eg ('xx@yy.com','093433343'...);string of list containing emails and phones
	//$contact structure of contact without the ['Contact']
	//return structure of ['Contact'] expects to have name and last
	//$relation: child, first-parent etc
	
	public function getContactsByuser($user){
	
		$query=	"select distinct
				Contact.*
				from contacts as Contact
				left join  contacts_relations as CR on(contact.id=CR.related_contact_id)
				inner join members as MEM on  (CR.contact_id=MEM.contact_id or CR.related_contact_id=MEM.contact_id or Contact.id=MEM.contact_id)
				 inner join groups_users as GU on(GU.member_id=Mem.id)
				where GU.user_id=".$user;
		$return=$this->query($query);
		return $return;
	}
	public function getContactsByusername($username){
		$query="select Contact.* from contacts as Contact
				  left join contacts_relations as CR
						on (Contact.id=CR.related_contact_id)
				  inner join members as Mem
					on (Contact.id=MEM.contact_id or CR.contact_id=MEM.contact_id or CR.related_contact_id=MEM.contact_id)
				 inner join groups_users as GU
					on(Mem.id=GU.member_id)
				inner join users as User
				on (User.id=GU.user_id)
				where Contact.email='".$username."' or Contact.cellphone='".$username."'";
		$return=$this->query($query);
		return $return;
	}
	public function getContactsByrelationUser($user,$relation=""){
		$cond="";
		$rel="";
		if ($relation=="child-member"){
			$query=	"select distinct Contact.name,
				contact.last,
				contact.id
				from contacts as Contact
				inner join contacts_relations as CR on(contact.id=CR.contact_id)
				inner join members as MEM on (CR.contact_id=MEM.contact_id)
				inner join groups_users as GU on(GU.member_id=Mem.id)
				where GU.user_id=".$user;
	
		}
		else {
			if (($relation<>"parent")&&($relation<>"")) {
				$relation_id=$this->Group->Term->getTermIdByValue($relation,'role-in-family');
				$rel=" and CR.relation_id=".$relation_id." ";
			}
			$query="select distinct Contact.name,
				Contact.last,
				Contact.email,
				Contact.cellphone,
				Contact.id
				from contacts as Contact
				inner join contacts_relations as CR on(contact.id=CR.related_contact_id)
				inner join members as MEM on (CR.related_contact_id=MEM.contact_id or CR.contact_id=MEM.contact_id)
				inner join  groups_users as GU on(GU.member_id=Mem.id)
				where GU.user_id=".$user.$rel.' order by CR.relation_id ASC LIMIT 1';
			//we do the order by to first look for the mother (8) and if not found go to father 9
		}
		$return=$this->query($query);
		if (count($return)==1) return $return[0];
		return $return;
	}
	//input $return=['Member']['Contact']
	//				['User']
	//output updated ['Contact'] structure
	
	
	function check_duplicate_member($member,$group_id){
		$this->recursive=-1;
		$result=$this->find('first',array(
				'conditions'=>array('GroupsUser.group_id'=>$group_id,),
				'joins'=>array(array('table'=>'members',
						'alias'=>'Member',
						'conditions'=>array(
								'Member.id=GroupsUser.member_id',
								'Member.contact_id'=>$member['contact_id'],)
				))
		));
		return $result;
	
	}
	function get_members_id_by_status($group_id=null){
		if ($group_id==null) $where="";
		else  $where='and GU.group_id='.$group_id;
		if(isset($order)) $order=' order by GU.group_id';
		
			$query='select distinct
				Member.*,
				Contact.email
				from contacts as Contact
				inner join members as Member on (Contact.id=Member.contact_id)
				inner join  groups_users as GU on(GU.member_id=Member.id)
				where Member.status<1 '.$where;
			return $this->query($query);
	}
	function get_members_by_group($group_id,$grouprole,$order,$current_user_contact=null){
		if(isset($order)) $order=' order by '.$order;
		if(is_array($grouprole)) $grouprole= '"'.implode('","',$grouprole).'"';
		if($grouprole<>"child-member")
			$query='select distinct
				Member.id,
				Member.member_type,
				GU.user_id,
				Contact.*
				from contacts as Contact
				inner join members as Member on (Contact.id=Member.contact_id)
				inner join  groups_users as GU on(GU.member_id=Member.id)
				where GU.group_id='.$group_id.' and Member.member_type in('.$grouprole.') '.$order;
	
		else
			$query='select distinct
				Member.id,
				Member.member_type,
				Contact.*,
				first_parent.*,
				second_parent.*,
				GU.user_id
				from contacts as Contact
				inner join contacts_relations as CR on(Contact.id=CR.contact_id)
				inner join members as Member on (CR.contact_id=Member.contact_id)
				inner join  groups_users as GU on(GU.member_id=Member.id)
				left join contacts as first_parent on (CR.related_contact_id=first_parent.id and CR.relation_type="first-parent")
				left join contacts as second_parent on (CR.related_contact_id=second_parent.id and CR.relation_type="second-parent")
				where GU.group_id='.$group_id.' and Member.member_type="child-member"'.$order;

		$members=$this->query($query);
        
		$i=0;
		$sec_row=false;
		$return=array();
		foreach ($members as $key=>$member){
			if($sec_row){
				$sec_row=false;
				continue;
			}
			$return[$i]['Member']=$member['Member'];
			$return[$i]['Contact']=$member['Contact'];
			$return[$i]['GroupsUser']=$member['GU'];
			$contact_id=$member['Contact']['id'];
			$sec_row=((isset($members[$key+1]))&&($members[$key+1]['Contact']['id']==$contact_id));
			$mother=(empty($member['first_parent']['name']))?((!$sec_row)||(empty($members[$key+1]['first_parent']['name'])))?false:$members[$key+1]['first_parent']:$member['first_parent'];
			if($mother) $return[$i]['Contact']['ContactsRelation'][]=$mother;
			$father=(empty($member['second_parent']['name']))?((!$sec_row)||(empty($members[$key+1]['second_parent']['name'])))?false:$members[$key+1]['second_parent']:$member['second_parent'];
			if($father) $return[$i]['Contact']['ContactsRelation'][]=$father;
			$i++;
		}
	
	
		return $return;
	}
	function deleteByMemeber($member_id){
		$sql='Delete From members where id='.$member_id;
		$sql .='; Delete From groups_users where member_id='.$member_id;
			$this->query($sql);
			return true;
	}
	
function activateMember($member_id,$user_id){
		$gu=$this->findByMemberId($member_id);
		if(count($gu)==0) return false;
		$this->Member->id=$member_id;
		//second user that is authorized for this group
		if (!empty($gu['GroupsUser']['user_id']) && $gu['GroupsUser']['user_id']<>$user_id ){
			$newgu['GroupsUser']['user_id']= $user_id;
			$newgu['GroupsUser']['group_id']= $gu['GroupsUser']['group_id'];
			$newgu['GroupsUser']['role_id']=6;
			$this->save($newgu);
		}
		else {
			$this->id=$gu['GroupsUser']['id'];
			$this->saveField('user_id',$user_id);
			$this->Member->saveField('status',1);
		}
		if($gu['Member']['member_type']=='child-member') return 'child-member'; else return 'staff';
	}
function setGU($temp){
  //  exit;
      
        $data['GroupsUser'][0] =$temp['GroupsUser'];
        unset($data['GroupsUser'][0]['Member']);
        //unset  ($data['GroupsUser']);
        if(empty($temp['user_id'])){
            $user=$this->User->findByContactId($temp['Contact']['id']);
           
            if ($user) $data['GroupsUser'][0]['user_id'] = $user['User']['id'];
            else 
                $data['GroupsUser'][0]['User']['contact_id']=$temp['Contact']['id'];

}
             if(isset ($temp['ContactsRelation'])){
                    //first parent
                    $user=$this->User->findByContactId($temp['ContactsRelation'][0]['related_contact_id']);
                    if($user) $data['GroupsUser'][1]['user_id'] = $user['User']['id'];
                    else {
                      $data['GroupsUser'][1]['User']['contact_id']=$temp['ContactsRelation'][0]['related_contact_id'];
                   //the rest of the user info from contact is done in beforesave in User class
                    }
                   $data['GroupsUser'][1]['member_id']=$temp['GroupsUser']['member_id'];
                   $data['GroupsUser'][1]['group_id']=$temp['GroupsUser']['group_id'];
                   $data['GroupsUser'][1]['role_id']=0;
                   $data['GroupsUser'][1]['activation_key']=$temp['GroupsUser']['Member']['activation_key'].'_1';
                   //second parent
                   if(isset ($temp['ContactsRelation'][1])){
                        $user=$this->User->findByContactId($temp['ContactsRelation'][1]['related_contact_id']);
                        if($user) $data['GroupsUser'][2]['user_id'] = $user['User']['id'];
                        else
                        $data['GroupsUser'][2]['User']['contact_id']=$temp['ContactsRelation'][1]['related_contact_id'];
                        $data['GroupsUser'][2]['member_id']=$temp['GroupsUser']['member_id'];
                       $data['GroupsUser'][2]['group_id']=$temp['GroupsUser']['group_id'];
                       $data['GroupsUser'][2]['role_id']=0;
                       $data['GroupsUser'][2]['activation_key']=$temp['GroupsUser']['Member']['activation_key'].'_2';   
            
                    }
             }
                       $return= $this->saveAll($data['GroupsUser'],array('deep'=>true)) ;  
                       if ($return) {
                            Cache::delete('element_mem_list1_'.$group,'groups_view');
    			            Cache::delete('element_mem_list2_'.$group,'groups_view');
                            return $data;} 
                       else return FALSE;
           
        }
        

   
}
