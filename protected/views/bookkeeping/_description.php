<?php if($this->show_link_on_description): ?>
  <?php echo CHtml::link(
    $posting->post->description,
    $this->createUrl('bookkeeping/journal', array('slug'=>$this->firm->slug, 'post'=>$posting->post_id)),
    array('class'=>'hiddenlink')
  ) ?>
<?php else: ?>
  <?php echo $posting->post->description ?>
<?php endif ?>
