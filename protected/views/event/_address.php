<?php if($event->address != '127.0.0.1'): ?>
  <?php echo CHtml::link($event->address, Yii::app()->params['iplocator'], array('target'=>'_blank')) ?>
<?php endif ?>
