<span title="<?php echo $event->action ?>"><?php echo $event->actionDescription ?></span>
<?php if($event->externalReferer): ?>
  <span title="<?php echo $event->externalReferer ?>">
  <?php echo $this->createIcon('referer', Yii::t('delt', 'referer'), array('width'=>16, 'height'=>16)) ?>
  </span>
<?php endif ?>

