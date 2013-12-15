<?php echo $this->Form->create('ImportFile',array('url'=>"/importgroup",'type' => 'file',));?>
						<h4><?php echo __('Excell file')?></h4>
						<?php	echo $this->Form->input('import_file', array('label' => __('Upload File'), 'type' => 'file',));?>
						<?php echo $this->html->link(__("Download Excel File Format"),'/files/contact_list.xls',array('class'=>"btn"));?>
						<br/>
						
						<p class="instructions"><?php echo __("Upload Excel File for instance response");?><br/>
							<?php echo __("Use the Excel Format to create you Excel Sheet");?><br/>
							<?php echo __('Please make sure each member has at list an email or cellular number')?>
						</p>
						<hr>
						<h4><?php echo __('Word or PDF files')?></h4>
						<?php	echo $this->Form->input('import_pdf', array('label' => __('Upload File'), 'type' => 'file',));?>
						<p class="instructions"><?php echo __("You can upload PDF or Word file.");?><br/>
							<?php echo __('Process time might be up to 72 hours')?>
						</p>
						<hr>
						<?php	echo $this->Form->input('SendEmail', array('label' => __('Send an invitation email'),'type'=>'checkbox','checked'=>1 ));?>
						<?php	echo $this->Form->input('emailtext', array('label'=>false,'placeholder' => __('Add your personal text to the inviting Email'),'type'=>'textarea' ));?>
						
						<?php echo $this->Form->submit(__('Submit',true),				
						 array(     'class' => 'btn green_gradient','style'=>'width:100%'  ));
echo $this->Form->end();?>
