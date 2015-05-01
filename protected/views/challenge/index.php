<?php
/* @var $this ChallengeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
  'Challenges',
);

$this->menu=array(
  array('label'=>'Create Challenge', 'url'=>array('create')),
  array('label'=>'Manage Challenge', 'url'=>array('admin')),
);
?>

<h1>Challenges</h1>

<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'_view',
)); ?>
