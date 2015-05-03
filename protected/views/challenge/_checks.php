<?php if(isset($source['errors']) && sizeof($source['errors'])): ?>
  <div class="errors">
  <?php foreach($source['errors'] as $error): ?>
    <p class="error"><?php echo $this->createIcon('exclamation', Yii::t('delt', 'Error'), array('width'=>16, 'height'=>16)) ?> <?php echo $error ?></p>
  <?php endforeach // errors?>
  </div>
<?php else: ?>
  <p class="ok"><?php echo $this->createIcon('accept', Yii::t('delt', 'OK'), array('width'=>16, 'height'=>16)) ?></p>
<?php endif ?>
