<?php if($this->show_link_on_description): ?>
  <?php echo CHtml::link(
    $posting->journalentry->description,
    $this->createUrl('bookkeeping/journal', array('slug'=>$this->firm->slug, 'journalentry'=>$posting->journalentry_id)),
    array('class'=>'hiddenlink')
  ) ?>
<?php else: ?>
  <?php echo $posting->journalentry->description ?>
<?php endif ?>
