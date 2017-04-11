<?php echo $this->renderPartial('_value', array('value'=>$posting->debit), true) ?>
<?php if ($posting->debit>0): ?><span class="rawvalue" id="posting_<?php echo $posting->id ?>" data-rawvalue="<?php echo $posting->debit ?>" /><?php endif ?>
