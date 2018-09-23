<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $this->firm->name => array('/bookkeeping/manage', 'slug'=>$this->firm->slug),
  'Sections' => array('/section/admin', 'slug'=>$this->firm->slug),
  'Create',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Sections'), 'url'=>array('admin', 'slug'=>$this->firm->slug)),
);
?>

<h1><?php echo Yii::t('delt', 'Create Section') ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
