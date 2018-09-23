<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $this->firm->name => array('/bookkeeping/manage', 'slug'=>$this->firm->slug),
  'Sections' => array('/section/admin', 'slug'=>$this->firm->slug),
  $model->name,
  'edit',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Manage Sections'), 'url'=>array('admin', 'slug'=>$this->firm->slug)),
	array('label'=>Yii::t('delt', 'Create Section'), 'url'=>array('create', 'slug'=>$this->firm->slug)),
	array('label'=>Yii::t('delt', 'View Section'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('delt', 'Edit section «{section}»', array('{section}'=>$model->name)) ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
