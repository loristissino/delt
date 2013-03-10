<?php if($this->show_link_on_description): ?>
  <?php echo CHtml::link($debitcredit->post->description, $this->createUrl('bookkeeping/journal', array('slug'=>$this->firm->slug, 'post'=>$debitcredit->post_id))) ?>
<?php else: ?>
  <?php echo $debitcredit->post->description ?>
<?php endif ?>
