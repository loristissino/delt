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
<?php elseif($with_oks): ?>
  <?php if(isset($source['penalties']) && $source['penalties']): ?>
    <?php echo Yii::app()->controller->createIcon('helped', Yii::t('delt', 'OK with help'), array('width'=>16, 'height'=>16)) ?>
  <?php else: ?>
    <?php echo Yii::app()->controller->createIcon('accept', Yii::t('delt', 'OK'), array('width'=>16, 'height'=>16)) ?>
  <?php endif ?>
<?php endif ?>
</div>
