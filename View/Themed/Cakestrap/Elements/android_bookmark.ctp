<script>
	$(function(){
		 $('#bookmark_chrom_a').popover({
			html:true,
			title:function(){return $(this).parent().find('.popover-title1').html();},
			content:function(){return $(this).parent().find('.popover-content').html();},
			placement:'bottom',
			container: 'body',
		});
	});
	</script>
 
                    <a href="#" rel="popover" class="pull-left" id="bookmark_chrom_a" style="display: block;width: 20px" href="<?php echo $this->html->url("/")?>"><?php echo $this->html->image('Star_max-b.png',array('class'=>'star','style'=>'width:100%','title'=>__("Add Shortcut")))?> 
           <i class="icon-hand-up icon-2x " style="z-index: 1000;color:#fff900;position: absolute;left: -10px;top: 0"></i> </a>
		<h3 class="popover-title1 hide"><?php echo __("Add Shortcut") ?></h3>
		<div class="popover-content hide">

<p>לחץ על כפתור ההגדרות  (פסים או שלוש נקודות אנכיות) - ואז לחץ על הכוכב.</p>
<?php echo $this->html->image('android.jpg')?>
<p>לחץ על שמור</p>
<p>"לחץ שוב על כפתור ההגדרות  (פסים או שלוש נקודות אנכיות) -בחר באפשרות "סימניות (Bookmarks).</p>

<p>לחץ לחיצה ארוכה על הסמל הירוק של be-member</p>
<?php echo $this->html->image('android2.jpg')?>
<p>בתפריט שנפתח בחר הוסף למסך הבית </p>
		</div>
