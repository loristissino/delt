<?php if($firm->banner): ?>
  <?php echo CHtml::image($this->createUrl('firm/banner', array('slug'=>$firm->slug)), null, array('width'=>640, 'height'=>80)) ?>
<?php endif ?>
