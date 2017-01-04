<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title =>array('exercise/view', 'id'=>$model->id),
  'Report'
);

$this->layout = '//layouts/column1_menu_below';

$this->menu=array(
  array('label'=>'View Exercise', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Invite Users', 'url'=>array('invite', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$model->title)) ?></h1>

<?php if($challenges): ?>

  <?php if(sizeof($challenges)): ?>
    <table>
      <tr>
        <th>Firm</th>
        <th>Owner</th>
        <th>Assigned</th>
        <th>Completed</th>
        <th>Checked</th>
        <th>Rate</th>
      </tr>
    <?php foreach($challenges as $challenge): ?>
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
  <?php else: ?>
    <div><?php echo Yii::t('delt', 'No one invited.') ?></div>
  <?php endif ?>

<?php endif ?>

<?php if ($sessions): ?>
  <h2><?php echo Yii::t('delt', 'Sessions') ?></h2>
  <?php if(sizeof($sessions)): ?>
    <ul>
    <?php foreach($sessions as $session): ?>
       <li>
          <?php echo CHtml::link($session['session'], array('exercise/report', 'id'=>$model->id, 'session'=>$session['session'])) ?>
          (<?php echo $session['cnt'] ?>)
       </li>
    <?php endforeach ?>
    </ul>
  <?php else: ?>
    <div><?php echo Yii::t('delt', 'No sessions.') ?></div>
  <?php endif ?>

<?php endif ?>
