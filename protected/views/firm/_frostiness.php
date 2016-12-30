<?php if($model->frozen_at): ?>
<p><?php echo $this->createIcon('frozen', Yii::t('delt', 'Frozen firm'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Frozen firm'))) ?>
 <?php echo Yii::t('delt', 'The firm «{firm}» has been frozen on <a href="{url}" target="_blank" title="{title}">{date}</a>.', array('{firm}'=>$model->name, '{date}'=>Yii::app()->dateFormatter->formatDateTime($model->frozen_at, 'full', 'short'), '{title}'=>Yii::t('delt', 'See this timestamp in a different timezone'), '{url}'=>Yii::t('delt', Yii::app()->params['fixedtimeUrl'], array('{iso}'=>Yii::app()->dateFormatter->format('yyyyMMddTHHmmss', ($model->getFrozenAtTimestamp() - Yii::app()->params['fixedtimeOffset'])))))) ?>
<?php if($warning): ?>
<br />
<?php echo $this->createIcon('bell', Yii::t('delt', 'Linked to completed challenge'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Linked to completed challenge'))) ?>
 <?php echo Yii::t('delt', 'The firm is linked to a challenge that has been marked completed, and this would be invalidated if you unfreeze it.') ?>
<?php endif ?>
 </p>
<?php else: ?>
<p><?php echo Yii::t('delt', 'The firm «{firm}» is not currently frozen.', array('{firm}'=>$model->name)) ?></p>
<?php endif ?>
