<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  'Sessions',
);

$this->layout = '//layouts/column2';

$this->menu=array(
  //array('label'=>Yii::t('delt', 'View Exercise'), 'url'=>array('view', 'id'=>$model->id)),
  //array('label'=>Yii::t('delt', 'Invite Users'), 'url'=>array('invite', 'id'=>$model->id)),
);

?>

<h1><?php echo Yii::t('delt', 'Sessions') ?></h1>

<?php if(sizeof($sessions_grouped)): ?>
    <?php foreach($sessions_grouped as $id=>$exercise): ?>
        <h2><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$exercise['title'])); ?></h2>
        <ul>
        <?php foreach($exercise['sessions'] as $session=>$count): ?>
            <li><?php echo CHtml::link($session, array('exercise/report', 'id'=>$id, 'session'=>$session)) ?> (<?php echo $count ?>)</li>
        <?php endforeach ?>
        </ul>
    <?php endforeach ?>
<?php else: ?>
    <div><?php echo Yii::t('delt', 'No sessions.') ?></div>
<?php endif ?>
