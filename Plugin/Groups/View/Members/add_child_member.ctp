<?php echo $this->Html->script('jquery.validation');?>
<style>
    #content{overflow: hidden;}
</style>
<script>
function check_parent(){
	        if($("#ContactsRelationParentCellphone").val()=="" && $("#ContactsRelationParentEmail").val()=="")
	          {
	  	        $("#error_adult").html('<?php echo __('Please provide with cellphone or email'); ?>');
	  	        return false;
	          }
  
            if ($("#contact-form").validation()) {
		        $("#ContactsRelationContactLast").val($("#ContactsRelationParentLast").val());
                $("#ContactsRelationContactCityId").val($("#ContactsRelationParentCityId").val());
                 $("#ContactsRelationContactAddress").val($("#ContactsRelationParentAddress").val());
               $("#ContactsRelationContactPhone").val($("#ContactsRelationParentPhone").val());

                          click_add_child();
    next_step(1,'child-div');
	        }
}
function afterGetContact($data){
            alert ($data);}

 function afterGetContactChildren(data){
               if(data=='-1'){
                    $("#ContactsRelation0ParentEmail").val($("#email").val());
                    $("#find-by-email").hide().fadeOut();
                    $("#contact-form").show().fadeIn("slow");
                
    }
     else{
              parent_id=$(data).find('#parent_id').val();  
               $("#ContactsRelation0ParentId").val(parent_id);
                    $("#contact-form").hide();
                var sec=$(data).find("#dddsecond").html();
               var child=$(data).find("#dddchildren").html();
                 var parent_name=$(data).find("#parent_name").html();
                 var parent_last=$(data).find("#parent_last").html();
   
                    $("#li-children-list").append(child);
                $("#li-children-list").removeClass('hide');
                $("#second-list").html(sec);
                  var parent_id=$("#parent_id").html();
              var fl=$(data).find("#firstparentlist").html();
                $("#firstparent-list").html(fl);
     
                 $("#ContactLast").val(parent_last);
                 $("#ContactsRelation0RelatedContactId").val(parent_id);
                 $("#summery-first").html(parent_name+" " +parent_last);
                 $("#summery-first").removeClass("hide");
                    $("#children-list").removeClass('hide');
                    next_step(1,'child-div');
                }
    }


function submit_add_member(){
            $("#AddMemberForm").submit();
            }

function check_child(){
	        if ($("#add_child").validation()) {
		        $("#ContactsRelationParentLast").val($("#ContactsRelationContactLast").val());
                $("#ContactsRelationParentCityId").val($("#ContactsRelationContactCityId").val());
		        next_step(-1);
            if($("#ontact-form").validation())
	        $("#AddMemberForm").submit();
	        }
}
function next_step(i,div){
    
    $('#'+div).show('swing');
    $('#'+div +" .collapse").collapse('toggle');
            if (i<-1) next_step(i+1);
	         //if (i>0) $('.carousel').carousel(i-1);
        }
function select_second_parent(parent_id){
            $("#ContactsRelation1RelatedContactId").val(parent_id);
             $("#summery-second").html($("#selectsecondparent option:selected").text());
            $("#summery-second").removeClass('hide');

             next_step(-2);
    }
function click_add_child(){
    $("#add-child-button").hide();
    // $('#child-div .collapse").collapse('show');
    	        $("#add_child").find("input[xxx]").each(function( ){
		        $(this).attr("required","required");
	        });
	        $("#add_child").find(".xxx").each(function( ){
		        $(this).addClass('required');
	        });
	        $("#add_child").show();
}
function child_selected($id){

            //we can now add the member, if we have its contact id
            $("#ContactId").val($id);
            $("#AddMemberForm").submit();
    alert($("#ContactId").val());
            }
$(function(){
    
            $( "body" ).delegate( "#checkEmail",'click',function(){
            if ($("#children-list").length==0)
            get_contact_children($("#email").val());
            });
   $( "body" ).delegate( "#children-list li","click",function(){
        child_selected($(this).attr("id"));
    });
	        $('accordion').collapse({
  toggle: false
});
	        if($('#add_child .error-message').length>0){
		        $('#secondstep').removeClass('active');
		        $('#add_child').addClass('active');
	        }
	        else {if($('#contact-form .error-message').length>0){
		        $('#secondstep').addClass('active');
		        $('#add_child').removeClass('active');
	        }}
	
});
	</script>

<?php
     function add_accordion_item($i,$title,$content,$options){
     $title_extra=isset($options['title_extra'])?$options['title_extra']:"";
     $active=isset($options['active'])?$options['active']:false;
     $div_id=isset($options['id'])?'id="'.$options['id'].'"':"";
       $div_class=isset($options['div_class'])?$options['div_class']:"";
    $in=($active)?'in':" ";
    echo '<div '.$div_id.' class="panel panel-default '.$div_class.'">';
    echo '<div class="panel-heading">';
    echo   '<div class="panel-title"><h4>';
     echo   '   <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'">';
     echo $title.'</a> </h4>'.$title_extra.'</div></div>';
    echo     '<div id="collapse'.$i.'" class="panel-collapse collapse '.$in.'">';
    echo   '<div class="panel-body">';
    echo $content;
    echo  '</div>    </div>  </div>';
 }
?>
<div class="row" style="padding-top:2%">
	<div class="span9">
<?php echo $this->croogoForm->create('Main',array('type'=>'file','id'=>'AddMemberForm'));?>
<?php //echo $this->CroogoForm->input('Contact.id',array('type'=>'hidden','id'=>'contactIdinForm')); ?>
<?php echo $this->CroogoForm->input('ContactsRelation.0.related_contact_id',array('type'=>'hidden')); 
$contact_id=(isset($contact['User']['Contact']['id']))? $contact['User']['Contact']['id']:null;
$user_contact=(isset($contact['User']['Contact']['id']))? $contact['User']['Contact']['id']:null;
                                   ?>
       
<div class="panel-group" id="accordion">
             <!-----------------parent --------------------->
       <?php if ($member_type<>'child-member') $title= __('Add New Staff member'); else $title= __('First Parent'); 
  $content1='<div id="find-by-email">'.$this->CroogoForm->input('Parent.email',array('id'=>'email','type'=>'email','label'=>__("First Parent Email")));
  $content1 .= '<div id="button-save-no-child" style="display: inline-block" >	
				<a href="#" id="checkEmail">
                <i style="color: black;border-radius: 4px" class="add-on icon-large icon-search btn green_gradient color-black"></i></a>
                </div></div>';
    $content1 .='<div id="contact-form" style="display:none" >';
    $content1 .='<div id="error_adult" class="alert-error"></div>';
         
    if ($member_type=='child-member')
		 $content1 .= $this->element('Contacts.AddEditAdult', array('contact_type'=>'parent','parent'=>'ContactsRelation.0.Parent',));
	else
		 $content1 .= $this->element('Contacts.AddEditAdult', array('contact_type'=>'staff','parent'=>"GroupsUser.Member",));
          $content1 .='<div id="button-save-no-child" >';	
		$content1 .= $this->html->link(__('Next'),'javascript:check_parent();'	,array('class'=>'btn green_gradient'));
		$content1 .= '</div><div style="margin-top:2%"></div></div>';
          echo add_accordion_item (1,$title ,$content1,array("active"=>TRUE)  );  
        ?>
        
         <!-----------------Child --------------------->
      <?php
          $title_extra= '<div class="menu-button" id="add-child-button"><a href="#" onClick="click_add_child()"><i class="icon-plus"></i>'. __('Add child to Family').'</a></div>';
          $content = '<div style="display: none" id="add_child">';
           $add_child=$this->element('Contacts.form',array('contact_type'=>'child-member',$user_contact))	;
			if(isset($children) && $children) $add_child=str_replace("required","xxx",$add_child);
			 $content .= $add_child;
             $content .= '<div id="button-child-info" >';
			$content .=	$this->html->link(__('Save and View Group'),'javascript:submit_add_member()'	,array('class'=>'btn green_gradient'));
            $content .='</div></div>';
            //<!------List of children that belong to parent -->
            $content .='<div id="li-children-list" class="hide">';
            $content .='<div id="firstparent-list"></div></div>';
            
           echo add_accordion_item (2, __('Child') ,$content, array('id'=>'child-div','title_extra'=>$title_extra,'div_class'=>'bottom-out span9') );  
        
    // <!-----------------Select second parent----------------->
                   
                           
                   $content='<div id="second-list"></div>';
		           $content .=   '<div id="button-seach-second" style="display: inline-block" >	';
                   $content .=      ' <a href="#" id="checkEmailSecond"><i style="color: black;border-radius: 4px" class="add-on icon-large icon-search btn green_gradient color-black"></i></a>';
                      $content .=   '</div><a href="#" id="skipSecond" onClick="skipSecond()" class="btn green_gradient color-black">'. __('Skip second parent step') .'</i></a>';
                      add_accordion_item(3, __('Set Second Parent'),$content,array('div_class'=>'bottom-out span9','id'=>'second-div'));?>
                      </div>
       </div>
  </div>
		<?php echo $this->Form->input('redirect',array('type'=>'hidden','value'=>'/groupsview'));
			echo $this->Form->input('GroupsUser.Member.member_type',array('type'=>'hidden'));
		 echo $this->Form->submit('submit',array('class'=>'hide'));
		echo $this->Form->end();?>
</div>
</div>
