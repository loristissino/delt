<?php
/* @var $this FirmController */

$this->layout='//layouts/column2';

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Activity Log',
);
?>

<article>
<h1><?php echo CHtml::encode($model->name) ?></h1>
<p><?php echo CHtml::encode($model->description) ?></p>
<section>
<h2><?php echo Yii::t('delt', 'Activity Log') ?></h2>
<p>
<?php foreach($events as $event): ?>
		<?php echo $event->happened_at ?>: 
		<strong title='<?php echo $event->content ?>'><?php echo $event->getActionDescription() ?></strong>
		(<?php echo $event->user ?>)
		<br />

<?php endforeach ?>
</p>
</section>
</article>
