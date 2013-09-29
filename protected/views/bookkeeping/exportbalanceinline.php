<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Balance' => array('/bookkeeping/balance', 'slug'=>$model->slug),
  'Export' => array('/bookkeeping/balance', 'slug'=>$model->slug, 'format'=>'unknown'),
  'Inline', 
);

?>
<h1><?php echo Yii::t('delt', 'Trial Balance Export') ?></h1>

<p class="note">
  <?php echo Yii::t('delt', 'You can copy the following lines and paste them to a spreadsheet.') ?>
</p>

<?php echo CHtml::textArea('data', $content, array('cols'=>60, 'rows'=>sizeof(explode("\n", $content)))) ?>
