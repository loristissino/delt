<?php echo CHtml::link(
  '@'.$subchoice,
  $this->createUrl('bookkeeping/subchoice', array('slug'=>$this->firm->slug, 'subchoice'=>$subchoice)),
  array('class'=>'hiddenlink subchoicelink')
  ) ?>
