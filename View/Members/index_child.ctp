
<div id="childmember" class="members index" >
	<a href="#" onClick='javascript:$("#mem_tables").scrollTo($("tr[id^=\"<?php echo __('a')?>\"]").first(),10);'><?php echo __("a-h")?></a>
	<a href="#" onClick='javascript:$("#mem_tables").scrollTo($("tr[id^=\"<?php echo __('i')?>\"]").first(),10);'><?php echo __("i-o")?></a>
	<a href="#" onClick='javascript:$("#mem_tables").scrollTo($("tr[id^=\"<?php echo __('p')?>\"]").first(),10);'><?php echo __("p-z")?></a>
</div>
	
<div id="mem_tables" style="">
	<table  class="nice"  cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo __('image');?></th>
			<th><?php echo __('Name');?></th>
			<th><?php echo __('Details');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	</thead>
	<?php
	$i = 0;
	foreach ($members as $member):
		$cur_user=false;
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tbody>
	<tr id="<?php echo $member['Contact']['name'].' '.$member['Contact']['last']?>" class="<?php echo $member['Terms']['slug']?>">
		<td>
			<?php if ($member['Contact']['image'] <> null) echo $this->Html->image($member['Contact']['image'],array('width'=>60)) ?>
			</td>
		<!--Name-->
		<td>
			<span class="mem_tab name"><?php echo $member['Contact']['name'] ?></span>
			<span class="mem_tab name"><?php echo $member['Contact']['last'] ?></span>
		</td>
		<!--Details-->
		<td>
			<table  cellpadding="0" cellspacing="0">
				<?php

foreach ($member['Contact']['ContactsRelation'] as $family_mem):
				//if not a childe
					if ($family_mem['email']==$useremail) $cur_user=true;
					?>
					<tr><td>
						<?php
							echo '<span class="mem_tab name">'.$family_mem['name']."</span>";
							echo '<span class="mem_tab name">'.$family_mem['last']."</span>";
							?>
						</td>
						<td>
						<?php
						echo '<phone><span class="mem_tab">'.$family_mem['cellphone']."</span></phone>";
						echo '<email><span class="mem_tab">'.$family_mem['email']."</span></eamil>";
						?>
						</td>
					</tr>
			<?php endforeach; ?>		
		</table>
		<?php if (isset($member['Contact']['phone'])) echo '<phone><span class="mem_tab">'.__("Home").": ". $member['Contact']['phone']."   </span></phone>";

			 if (isset($member['Contact']['address'])) echo '<span class="mem_tab">'.__("Address").": ". $member['Contact']['address']."</span>";
			?>
		</td>
		
		<td class="actions">
			
			<?php
			if (($cur_user)||($group_role['slug']=='master')){
				 echo $this->Html->link(__('Edit', true), array('action' => 'edit', $member['Member']['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $member['Member']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $member['Member']['id'])); 
			$cur_user=false;
			}?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
	</table>
</div>	
	<?php		// $this->Paginator->options(array('update' => '#childmember',  
										//		'evalScripts' => true,));
										?>
<div style="text-align: center;">
	  		<?php
	  		//if ($this->Paginator->hasPage(2)) {
	  		//	echo $this->Paginator->prev();
	  		//	echo (" | ");
	  		//} ?> 
	  		<?php //echo $this->Paginator->numbers(); ?> 
	  		<?php 
	  		//if ($this->Paginator->hasPage(2)) { 
	  		//	echo (" | ");
	  		//	echo $this->Paginator->next();
	  		//} ?>
	  		
	  		<?php //echo $this->Js->writeBuffer();*/ 
	  		?>
</div>



