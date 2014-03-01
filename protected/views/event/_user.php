<?php if($event->user): ?>
  <?php echo CHtml::link($event->user, array('user/admin/view', 'id'=>$event->user_id)) ?>
<?php endif ?>
