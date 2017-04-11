<?php echo $this->renderPartial('_value', array('value'=>$posting->credit), true) ?>
<?php if ($posting->credit>0): ?><span class="rawvalue" id="posting_<?php echo $posting->id ?>" data-rawvalue="<?php echo -$posting->credit ?>" /><?php endif ?>
