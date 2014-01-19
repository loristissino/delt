<?php
$symbols = array('IN'=>'⬆',    'IC'=>'⇧',   'DN'=>'⬇',   'DC'=>'⇩',    '?N'=>'?',     '?C'=>'?',  'nn'=>'⬄');
$colors  = array('IN'=>'blue', 'IC'=>'red', 'DN'=>'red', 'DC'=>'blue', '?N'=>'black', '?C'=>'black', 'nn'=>'#FFD300');
?><span style="color: <?php echo $colors[$change.$type] ?>"><?php echo $symbols[$change.$type] ?></span>
<?php if($change=='I'): ?>
  <?php echo Yii::t('delt', 'Increase') ?>
<?php elseif($change=='D'): ?>
  <?php echo Yii::t('delt', 'Decrease') ?>
<?php else: ?>
  <?php echo Yii::t('delt', 'Unknown') ?>
<?php endif ?>
