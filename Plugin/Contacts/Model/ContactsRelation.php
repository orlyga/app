<?php

App::uses('ContactsAppModel', 'Contacts.Model');

/**
 * Contact
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo.Contacts.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class ContactsRelation extends ContactsAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'ContactsRelation';

/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Croogo.Cached' => array(
			'groups' => array(
				'contacts',
			),
	),
	);

/**
 * Validation
 *
 * @var array
 * @access public
 */
	
	
	public $belongsTo = array(
			'Contact' => array('className' => 'Contacts.Contact','foreignKey' => 'contact_id',),
			'Parent' => array('className' => 'Contacts.Contact','foreignKey' => 'related_contact_id',),
					//'Term' => array('className' => 'Taxonomy.Term','foreignKey' => 'relation_id',),
			
	);
	
/**
 * Display fields for this model
 *
 * @var array
 */
 /*return s
 y
(
    [0] => Array
        ( [Contact] => Array
                (  [id] => 792
                    [name] => נעמהה
                    [last] => נעמהה
                )
          [ContactsRelation] => Array
                ( [related_contact_id] => 791
                )
        )

    [1] => Array
        (            [Contact] => Array*/
	public function getContactChildrenByEmail($email) {
		$this->recursive=-1;
        $query='SELECT id,last,name
                FROM  contacts Contact
                WHERE email =  "'.$email.'"';
		$result = $this->query($query);
        if ($result) {
           
             $query='SELECT DISTINCT Contact.id, Contact.name
                FROM contacts Contact
                INNER JOIN contacts_relations ContactsRelation ON ContactsRelation.contact_id = Contact.id
                INNER JOIN contacts a ON a.id = ContactsRelation.related_contact_id
                WHERE Contact.email =  "'.$email.'"';
		     $result2 = $this->query($query);
             $final=array();
             $final['Parent']=$result[0];
             $final['Children']=$result2;
             
            return $final;
            

        }
		else return $result;
	
	}
	
	public function getContactChildren($contact_id,$one_child=false) {
		$this->recursive=-1;
		$result=$this->find('all',array(
				'conditions'=>array('ContactsRelation.related_contact_id='.$contact_id,),
				'joins'=>array(array(
						'table'=>'contacts',
						'alias'=>'Contact',
						'type' => 'LEFT',
						
						'conditions'=>array('ContactsRelation.contact_id=Contact.id'))),
				'fields'=>array('Contact.*'),
				));
		return $result;
	
	}
	public function findContactChild($contact_id,$child) {
		
		$this->recursive=-1;
		if(!empty($child['id'])) $condition='Contact.id='.$child['id'];
		else $condition='Contact.name="'.$child['name'].'"';
		$result=$this->find('all',array(
				'joins'=>array(array(
						'table'=>'contacts',
						'alias'=>'Contact',
						'type' => 'LEFT',
	
						'conditions'=>array('ContactsRelation.contact_id=Contact.id',$condition))),
				'fields'=>array('Contact.*'),
				'conditions'=>array('ContactsRelation.related_contact_id='.$contact_id,),
		));
		return $result;
	
	}
	function getContactsOfRelatedByContactId($contact_id,$relation_type=null){
		if ($relation_type==null) $type_arr=array();
		else $type_arr=array('ContactsRelation.relation_type'=>$relation_type);
		$results=$this->find('all',array(
				'fields'=>array('Parent.*','ContactsRelation.relation_type'),
				'conditions'=>array(
								'ContactsRelation.contact_id'=>$contact_id,$type_arr),
				'recursive'=>0)
				);
		$i=0;		
		foreach ($results as $result) {
			$results[$i]['Parent']['relation_type']=$results[$i]['ContactsRelation']['relation_type'];
			unset($results[$i]['ContactsRelation']);
			$i++;
		}
		if( $i==1) return $results[0];
		return $results;
	}

    function getSecondParentsbyFirstParents($contact_id){
       $sql= "SELECT DISTINCT Contact.name,Contact.last,Contact.id
            FROM contacts Contact
            INNER JOIN contacts_relations ContactsRelation ON Contact.id = ContactsRelation.related_contact_id
            INNER JOIN contacts_relations ContactsRelationFirst ON ContactsRelation.contact_id = ContactsRelationFirst.contact_id
            AND ContactsRelation.related_contact_id <> ContactsRelationFirst.related_contact_id
            AND ContactsRelationFirst.related_contact_id =".$contact_id;
		$results=$this->query($sql);
		return $results;
	}

	function getContacts($contact_id,$mem_only=false){
		if (!$mem_only){
			$this->recursive=1;
			$result=$this->find('all',array(
					'conditions'=>array(
							'ContactsRelation.related_contact_id'=>$contact_id,
					)
			));
		}
		else {
			$res1[0]=$this->GetChildContactByMember($mem_only);
	
			$result=$this->Contact->find('all',array(
					'conditions'=>array(
							'Contact.id'=>$mem_only,
					)
			));
				
			$result = array_merge($res1,$result);
				
		}
		return $result;
	}
	function GetChildContactByMember($mem){
		$result=$this->Contact->find('first',array(
				'recursive'=>1,
				'conditions'=>array('Term.slug'=>'child'),
				'joins' => array(
						array('table' => 'members',
								'alias' => 'Members',
								'type' => 'INNER',
								'conditions' => array('Members.contact_id = Contact.id','Members.id'=>$mem,)
						),
				),
		)
		);
		return $result;
	}
	//expects ['ContactsRelation']['Parent']
	//['ContactsRelation']['contact_id']
	//['ContactsRelation']['relation_type'] to be set
	function checkContactsExists($data){
	$res=$this->Contact->isContactExist($data['ContactsRelation']['Parent']);
		//parent was found, need to check its children
		if ($res) {
		if(isset($data['ContactsRelation']['contact_id']))
			$data['ContactsRelation']['Contact']['id']=$data['ContactsRelation']['contact_id'];
		$contact=$this->_isChildExist($res['Contact']['id'],$data['ContactsRelation']['Contact']);
			
			//found the specific child
			if($contact) $data['found_child']=$contact;
			//only parent was found
			else
			{
				unset($data['ContactsRelation']['Parent']);
				$data['ContactsRelation']['related_contact_id']=$res['Contact']['id'];
			}
		}
		return $data;
	}
	//expects ['ContactsRelation']['Parent']
	//['ContactsRelation']['contact_id']
	//['ContactsRelation']['relation_type'] to be set
	function setParent($data){
		$data=$this->checkContactsExists($data);
		if(isset($data['found_child'])) return true;
		return $this->saveAll($data,array('deep'=>true));
	}
	function addChildContact($contact){
		$data=array('Contact'=>$contact,'ContactsRelation'=>array('relation_type'=>"first-parent",
				'related_contact_id'=>$contact['parent_id']));
	
		if($this->saveAll($data,array('deep'=>true))){
			return $this->getInsertID();
		}
		else return $data;
	}
//if not found even one child, returns false
//if found child but not the specific one, returns the last child that is similiar
//return child_id if a percise found
function _isChildOf($contactRelations){
    $in = "'".$contactRelations[0]['related_contact_id']."'";
    if (!empty($contactRelations[1]['related_contact_id'])) $in .=",'".$contactRelations[1]['related_contact_id']."'";
     $query = "select Contact.* from contacts as Contact inner join contacts_relations as CR 
     on Contact.id = CR.contact_id where CR.related_contact_id in (".$in.")";
    $contacts=$this->query($query);
     $name=$contactRelations[0]['Contact']['name'];
   if (count($contacts)>0){
			foreach ($contacts as $onechild){
				$lev_number=(strlen($name)<4) ? 1:3;
				$match_name=levenshtein( $name ,$onechild['Contact']['name'] );
				if (($match_name<=$lev_number)){
					return $onechild['Contact']['id'];
				}
			}
         return $onechild['Contact'];
		}
   return FALSE;

}
function setContactRelations($contactRelations){
   
    if (empty($contactRelations[0]['Contact']['name']) && ($contactRelations[0]['Contact']['id']))
       {
    			echo "error: no member selected";
    			return false;
    	}
         //new parent and new child
         //before save will check if exist, if does then return same contact structure
        if (!isset($contactRelations[0]['related_contact_id'])){
         $contact['Contact']=$contactRelations[0]['Parent'];
	            $firstparentContact=$this->Contact->save($contact);
                $contactRelations[0]['related_contact_id']=$this->Contact->id;
                
        }
       $secondparentContact="";
       $contactRelations[0]['relation_type']='first-parent';
        if(isset($contactRelations[1])) {
            $contact['Contact']=$contactRelations[1]['Parent'];
            $secondparentContact=$this->Contact->save($contact);
         
            $contactRelations[1]['related_contact_id']=$this->Contact->id;
            $contactRelations[1]['relation_type']='second-parent';
            unset($contactRelations[1]['Parent']);
       
        }
        if (empty($contactRelations[0]['Contact']['id'])){
           
            //try to find the child by parents and name
            $child_contact_id=$this->_isChildOf($contactRelations);
           
            //is array if we found another child in the family
            if(is_array($child_contact_id)) {
                $contactRelations[0]['Contact']=array_merge ($child_contact_id,$contactRelations[0]['Contact']);
                 return $contactRelations;

                           }
            elseif ($child_contact_id) {
                //need to add this child
                $contactRelations[0]['Contact']['id']= $child_contact_id;
                return $contactRelations;
            }
            //need to create a child based on first parent info
           else { 
               //get parent info if not there
               if(empty($contactRelations[0]['Parent']['email'])){
                   $par=$this->Contact->read(NULL,$contactRelations[0]['related_contact_id']);
                   unset($contactRelations[0]['Parent']);
                  
               }
              // $contactRelations[0]['Parent']['id']=$contactRelations[0]['related_contact_id'];
                  if(empty($contactRelations[0]['Contact']['email'])) $contactRelations[0]['Contact']['email']=$par['Contact']['email'];
                   if(empty($contactRelations[0]['Contact']['phone'])) $contactRelations[0]['Contact']['phone']=$par['Contact']['phone'];
                   if(empty($contactRelations[0]['Contact']['cellpone'])) $contactRelations[0]['Contact']['cellphone']=$par['Contact']['cellphone'];
                   if(empty($contactRelations[0]['Contact']['address'])) $contactRelations[0]['Contact']['address']=$par['Contact']['address'];
   
           }
           $con['Contact']=$contactRelations[0]['Contact'];
           //set new contact in the relations so we can save relations
           $ret=$this->Contact->saveAll($con);
         //  prt($this->Contact->getInsertId());
         //  exit;
          if($ret){
            $contactRelations[0]['Contact']['id']=$this->Contact->getInsertId();
          
            if (isset($contactRelations[1]))  $contactRelations[1]['Contact']['id']=$this->Contact->getInsertId();
           
            if ($this->saveAll($contactRelations,array('deep'=>true))) return $contactRelations;
            else return FALSE;
          }
          else return FALSE;
        }

}
	function setNewContantandChild($data,$check_exist=false){
	if(!isset($data['ContactsRelation'])){
		$tosave['ContactsRelation']['Parent']=$data['GroupsUser']['Member']['Contact'];
		$tosave['ContactsRelation']['Contact']=$data['GroupsUser']['Member']['Child'];
	}
	else $tosave=$data;
	if($check_exist){
		$tosave=$this->checkContactsExists($tosave);
		//if child was found, returns the contact_id of the child.
		if(isset($tosave['found_child'])) return $tosave['found_child'];
	}
	$tosave['ContactsRelation']['relation_type']='first-parent';
	if($this->saveAll($tosave,array('deep'=>true))){
		$contact=$this->Contact->getInsertId();
		return $contact;
		}
	else {
		//if array was returns then there was a problem
		//structure expected to be 
		//['ContactsRelation']['Parent']
		//['ContactsRelation']['Contact']
		return $tosave;
	}
		
		
	}
	private function _isChildExist($contact_id,$child){
	//we check if child related to this parent
		$res=$this->findContactChild($contact_id,$child);
		if ($res) return $res[0]['Contact']['id'];
		$res=$this->getContactChildren($contact_id,$child);
		$name=$child['name'];
		if (count($res)>0){
			foreach ($res as $onechild){
				$lev_number=(strlen($name)<4) ? 1:3;
				$match_name=levenshtein( $name ,$onechild['Contact']['name'] );
				if (($match_name<=$lev_number)){
					return $onechild['Contact']['id'];
				}
			}
		}
		return false;
		
	}
	function getUserbyContactChild($contact_id){
		$this->recursive=-1;
		$result=$this->find('first',array(
				'conditions'=>array('ContactsRelation.contact_id='.$contact_id),
				'joins'=>array(array(
						'table'=>'users',
						'alias'=>'Users',
						'type' => 'INNER',
	
						'conditions'=>array('Users.contact_id= ContactsRelation.related_contact_id'))),
				'fields'=>array('Users.id'),
		));
		return $result;
	}
	function test(){
		echo "hi";
		exit;
	}
	

}