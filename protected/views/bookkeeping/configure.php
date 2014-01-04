<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Statements configuration',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'Create new item'), 'url'=>array('account/create', 'slug'=>$model->slug, 'config'=>true)),
);

?>
<h1><?php echo Yii::t('delt', 'Statements configuration') ?></h1>

<?php echo $this->renderPartial('_coagrid', array('model'=>$model, 'dataProvider'=>$dataProvider, 'renderNameCallable'=>'RenderNameWithoutLink', 'buttonsTemplate'=>'{update}{new}')) ?>
