<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Closing entry',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Economic closing'), 'url'=>array('bookkeeping/closingpost', 'slug'=>$model->slug, 'position'=>'E')),
	array('label'=>Yii::t('delt', 'Profit/Loss'), 'url'=>array('bookkeeping/profitlosspost', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Patrimonial closing'), 'url'=>array('bookkeeping/closingpost', 'slug'=>$model->slug, 'position'=>'P')),
	array('label'=>Yii::t('delt', 'Memo closing'), 'url'=>array('bookkeeping/closingpost', 'slug'=>$model->slug, 'position'=>'M')),
);

?>
<h1><?php echo Yii::t('delt', $this->postdescription) ?></h1>

<?php if($position): ?>
<p><?php echo Yii::t('delt', 'This firm does not seem to have accounts of «{position}» position to close.', array('{position}'=>Account::model()->getValidpositionByCode($position))) ?></p>
<?php else: ?>
<p><?php echo Yii::t('delt', 'Please choose the kind of closing you need on the side menu.') ?></p>
<?php endif ?>
