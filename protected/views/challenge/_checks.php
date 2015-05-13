<div class="checks">
<?php if(isset($source['warnings']) && sizeof($source['warnings'])): ?>
  <div class="warnings">
  <?php foreach($source['warnings'] as $warning): ?>
    <p class="warning"><?php echo $this->createIcon('bell', Yii::t('delt', 'Warning'), array('width'=>16, 'height'=>16)) ?> <?php echo $warning ?></p>
  <?php endforeach // warnings ?>
  </div>
<?php endif ?>
<?php if(isset($source['errors']) && sizeof($source['errors'])): ?>
  <div class="errors">
  <?php foreach($source['errors'] as $error): ?>
    <p class="error"><?php echo $this->createIcon('exclamation', Yii::t('delt', 'Error'), array('width'=>16, 'height'=>16)) ?> <?php echo $error ?></p>
  <?php endforeach // errors ?>
  </div>
<?php else: ?>
<?php endif ?>
</div>
