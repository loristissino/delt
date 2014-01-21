<?php
$icons = array('IN'=>'increase_normal',    'IC'=>'increase_contra',   'DN'=>'decrease_normal',   'DC'=>'decrease_contra',    '?N'=>'question_mark',     '?C'=>'question_mark',  'nn'=>'question_mark');
?><?php echo $this->createIcon($icons[$change.$type], Yii::t('delt', 'Arrow'), array('width'=>16, 'height'=>16, ))?>
<?php if($change=='I'): ?>
  <?php echo Yii::t('delt', 'Increase') ?>
<?php elseif($change=='D'): ?>
  <?php echo Yii::t('delt', 'Decrease') ?>
<?php else: ?>
  <?php echo Yii::t('delt', 'Unknown') ?>
<?php endif ?>
