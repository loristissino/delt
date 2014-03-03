<?php if($event->firm): ?>
  <?php echo CHtml::link(CHtml::encode($event->firm->name), array('/firms/' . $event->firm->slug), array('title'=>$event->firm_id)) ?>
<?php endif ?>
<?php if($event->action == Event::SITE_PAGE_SEEN): ?>
  <?php $data = json_decode($event->content) ?>
  <?php echo $data->viewPath ?>
<?php endif ?>
