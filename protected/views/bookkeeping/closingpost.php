<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Closing post',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Economic closing'), 'url'=>array('bookkeeping/closingpost', 'slug'=>$model->slug, 'nature'=>'E')),
	array('label'=>Yii::t('delt', 'Profit/Loss'), 'url'=>array('bookkeeping/profitlosspost', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Patrimonial closing'), 'url'=>array('bookkeeping/closingpost', 'slug'=>$model->slug, 'nature'=>'P')),
	array('label'=>Yii::t('delt', 'Memo closing'), 'url'=>array('bookkeeping/closingpost', 'slug'=>$model->slug, 'nature'=>'M')),
);

?>
<h1><?php echo Yii::t('delt', $this->postdescription) ?></h1>

<?php if($nature): ?>
<p><?php echo Yii::t('delt', 'This firm does not seem to have accounts of nature «{nature}» to close.', array('{nature}'=>Account::model()->getValidNatureByCode($nature))) ?></p>
<?php else: ?>
<p><?php echo Yii::t('delt', 'Please choose the kind of closing you need on the side menu.') ?></p>
<?php endif ?>
