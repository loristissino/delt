<?php
/* @var $this FirmController */

$this->layout='//layouts/html5';
$this->pageTitle=Yii::app()->name . ' - ' . $model->name;
/*
$this->breadcrumbs=array(
  'Firms'=>array('/firm/index'),
  $model->name => array('/firm/public', 'slug'=>$model->slug),
  'Public',
);
*/

?>

<article>
<h1><?php echo CHtml::encode($model->name) ?></h1>
<p><?php echo CHtml::encode($model->description) ?></p>
<?php echo $this->renderPartial('_banner', array('firm'=>$model)) ?>
<section>
<h2><?php echo Yii::t('delt', 'Journal in ledger-cli\'s format') ?></h2>

<pre>
<?php echo $data ?>
</pre>

<?php echo $this->renderPartial('_frostiness', array('model'=>$model)) ?>

<?php echo $model->getLicenseCode($this) ?>

</article>
