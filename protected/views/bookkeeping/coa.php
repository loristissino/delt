<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Chart of accounts',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'Create new account'), 'url'=>array('account/create', 'slug'=>$model->slug)),
  array('label'=>Yii::t('delt', 'Import accounts'), 'url'=>array('account/import', 'slug'=>$model->slug)),
  array('label'=>Yii::t('delt', 'Export accounts'), 'url'=>array('account/export', 'slug'=>$model->slug)),
  //array('label'=>Yii::t('delt', 'Text list'), 'url'=>array('bookkeeping/coa', 'slug'=>$model->slug, 'template'=>'coatextlist')),
  //array('label'=>Yii::t('delt', 'Fix chart'), 'url'=>array('bookkeeping/fixaccountschart', 'slug'=>$model->slug)),
);
if($model->firm_parent_id)
{
  $this->menu[]=array('label'=>Yii::t('delt', 'Synchronize'), 'url'=>array('account/synchronize', 'slug'=>$model->slug), 'linkOptions'=>array('title'=>Yii::t('delt', 'Syncronize accounts from ancestor firms')));
}

$this->menu[]=array('label'=>Yii::t('delt', 'Configure'), 'url'=>array('bookkeeping/configure', 'slug'=>$model->slug), 'linkOptions'=>array('title'=>Yii::t('delt', 'Configure Financial Statement components')));

?>
<h1><?php echo Yii::t('delt', 'Chart of accounts') ?></h1>

<?php echo $this->renderPartial('_coagrid', array('model'=>$model, 'dataProvider'=>$dataProvider, 'renderNameCallable'=>'RenderName', 'buttonsTemplate'=>'{view}{update}{new}')) ?>
