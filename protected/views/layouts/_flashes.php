<?php if(Yii::app()->user->hasFlash('delt_success')): ?>
  <div class="success">
  <?php echo Yii::t('delt', Yii::app()->user->getFlash('delt_success')) ?>
  </div>
<?php endif ?>
<?php if(Yii::app()->user->hasFlash('delt_failure')): ?>
  <div class="failure">
  <?php echo Yii::t('delt', Yii::app()->user->getFlash('delt_failure')) ?>
  </div>
<?php endif ?>
