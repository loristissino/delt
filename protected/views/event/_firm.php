<?php if($event->firm): ?>
  <?php echo CHtml::link(CHtml::encode($event->firm->name), array('/firms/' . $event->firm->slug), array('title'=>$event->firm_id)) ?>
<?php endif ?>
