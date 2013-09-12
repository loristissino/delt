<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' =>array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Template creation',
);

$this->menu=array(
);

?>
<h1><?php echo Yii::t('delt', 'Template creation') ?></h1>

<p><?php echo Yii::t('delt', 'You are going to create a new template with the following accounts:') ?></p>
<ul>
<?php foreach($this->journalentry->postings as $posting): $type=DELT::amount2type($posting->amount) ?>
  <li>
  <?php echo $posting->account ?> (<?php echo Yii::t('delt', $type) ?>)
  </li>
<?php endforeach ?>
</ul>
<?php echo $this->renderPartial('_template', array('model'=>$template)) ?>
