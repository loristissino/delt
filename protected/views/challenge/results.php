<?php
/* @var $this ChallengeController */
/* @var $model Challenge */

$this->layout = 'column1';

$this->breadcrumbs=array(
  'Challenges'=>array('index'),
  $model->exercise->title,
  'Results',
);

$md = new CMarkdown();

?>

<h1><?php echo Yii::t('delt', 'Challenge «{title}»: results', array('{title}'=>$model->exercise->title)) ?></h1>

<div id="challenge">

  <h2><?php echo Yii::t('delt', 'Firm') ?></h2>
  <p><?php echo $model->firm ?></p>
  <?php $this->renderPartial('_checks', array('source'=>DELT::getValueFromArray($results, 'firm', array()), 'with_oks'=>true)) ?>
  <hr />

  <h2><?php echo Yii::t('delt', 'Transactions') ?></h2>
  <?php foreach (DELT::getValueFromArray($results, 'transactions', array()) as $transaction): ?>
    <div class="transaction">
      <?php echo $md->transform($transaction['description']) ?>
    </div>
    <?php $this->renderPartial('_checks', array('source'=>$transaction, 'with_oks'=>true)) ?>
    <div class="points extrainfo">
    <?php echo Yii::t('delt', 'Points: {points}. Penalties: {penalties}',
      array('{points}'=>$transaction['points'], '{penalties}'=>$transaction['penalties']))
    ?>
    </div>
    <hr />
  <?php endforeach // transactions?>

  <?php if(false!==$score=DELT::getValueFromArray($results, 'score', false)): ?>
  <h2><?php echo Yii::t('delt', 'Score') . ': '. Yii::t('delt', 'One point|{n} points', DELT::getValueFromArray($results, 'score', null)) . ' (' . 
    Yii::app()->numberFormatter->formatDecimal(round(1000*$score/DELT::getValueFromArray($results, 'possiblescore', 1)/10)) . '%)' ?></h2>
  <?php endif ?>

</div>
