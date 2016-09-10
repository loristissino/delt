<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title,
);

$this->layout = '//layouts/column1';

$this->menu=array(
  array('label'=>'List Exercise', 'url'=>array('index')),
  array('label'=>'Create Exercise', 'url'=>array('create')),
  array('label'=>'Update Exercise', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Exercise', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Exercise', 'url'=>array('admin')),
);
?>

<h1>View Exercise #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    'user_id',
    'firm_id',
    'slug',
    'title',
    'description',
    'introduction',
  ),
)); ?>

<?php if(sizeof($model->challenges)): ?>
	<table>
		<tr>
			<th>Firm</th>
			<th>Owner</th>
			<th>Assigned</th>
			<th>Completed</th>
			<th>Checked</th>
			<th>Rate</th>
		</tr>
	<?php foreach($model->challenges as $challenge): ?>
		<tr>
			<td>
				<?php if($challenge->firm): ?>
				<?php echo CHtml::link(CHtml::encode($challenge->firm->name), array('/firms/' . $challenge->firm->slug), array('title'=>$challenge->firm_id)) ?>
				<?php else: ?>
				<em><?php echo $challenge->user ?></em> 
				<?php endif ?>
			</td>
			<td title="<?php echo $challenge->user ?>">
				<?php if($challenge->firm): ?>
				<?php echo CHtml::encode($challenge->firm->getOwners(true)) ?>
				<?php endif ?>
			</td>
			<td><?php echo $challenge->assigned_at ?></td>
			<td><?php echo $challenge->completed_at ?></td>
			<td><?php echo $challenge->checked_at ?></td>
			<td style="text-align: right"><?php echo Yii::app()->numberFormatter->formatDecimal(round($challenge->rate/10)). '%' ?></td>		</tr>
	<?php endforeach ?>
	</table>
<?php endif ?>

