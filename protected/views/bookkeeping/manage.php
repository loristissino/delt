<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Manage',
);
?>
<h1><?php echo $model->name ?></h1>

<?php $this->widget('zii.widgets.CMenu', array(
    'items'=>array(
        array('label'=>Yii::t('delt','Bookkeeping'), 'items'=>array(
            array('label'=>Yii::t('delt','Chart of accounts'), 'url'=>array('/bookkeeping/coa', 'slug'=>$model->slug)),
            array('label'=>Yii::t('delt','Journal'), 'url'=>array('/bookkeeping/journal', 'slug'=>$model->slug)),
            array('label'=>Yii::t('delt','Trial balance'), 'url'=>array('/bookkeeping/balance', 'slug'=>$model->slug)),
        )),
        array('label'=>Yii::t('delt','Management'), 'items'=>array(
            array('label'=>Yii::t('delt','Edit / Update'), 'url'=>array('/firm/update', 'slug'=>$model->slug)),
            array('label'=>Yii::t('delt','Export'), 'url'=>array('/firm/export', 'slug'=>$model->slug)),
        ))
     ),
));
?>
