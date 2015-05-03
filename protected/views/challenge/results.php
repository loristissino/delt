<?php
/* @var $this ChallengeController */
/* @var $model Challenge */

$this->layout = 'column1';

$this->breadcrumbs=array(
  'Challenges'=>array('index'),
  $model->id=>array('view','id'=>$model->id),
  'Results',
);

$md = new CMarkdown();

?>

<h1><?php echo Yii::t('delt', 'Challenge «%title%»: results', array('%title%'=>$model->exercise->title)) ?></h1>

<div id="challenge">

  <h2><?php echo Yii::t('delt', 'Firm') ?></h2>
  <p><?php echo $model->firm ?></p>
  <?php $this->renderPartial('_checks', array('source'=>$results['firm'])) ?>
  <hr />

  <h2><?php echo Yii::t('delt', 'Transactions') ?></h2>
  <?php foreach ($results['transactions'] as $transaction): ?>
    <div class="transaction">
      <?php echo $md->transform($transaction['description']) ?>
    </div>
    <?php $this->renderPartial('_checks', array('source'=>$transaction)) ?>
    <div class="points extrainfo">
    <?php echo Yii::t('delt', 'Points: %points%. Penalties: %penalties%',
      array('%points%'=>$transaction['points'], '%penalties%'=>$transaction['penalties']))
    ?>
    </div>
    <hr />
  <?php endforeach // transactions?>

  <h2><?php echo Yii::t('delt', 'Score') . ': '. Yii::t('delt', 'One point|{n} points', $results['score']) . ' (' .  round(100*$results['score']/$results['possiblescore']) . '%)' ?></h2>

</div>
<?php /*
<pre>
<?php print_r($results) ?>
</pre>
*/ ?>