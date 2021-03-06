<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Closing entry',
);

$this->menu=array();

foreach($model->getMainPositionsForClosingEntries() as $mp)
{
  $this->menu[]=array('label'=>$mp->getClosingDescription(), 'url'=>array('bookkeeping/closingjournalentry', 'slug'=>$model->slug, 'position'=>$mp->position));
}

?>
<h1><?php echo Yii::t('delt', $this->journalentrydescription) ?></h1>

<?php if($position and $closing): ?>
<p><?php echo Yii::t('delt', 'This firm does not seem to have «{position}» accounts to close.', array('{position}'=>$closing->currentname)) ?></p>
<?php else: ?>
<p><?php echo Yii::t('delt', 'Please choose the kind of closing entry you need on the side menu.') ?></p>
<?php endif ?>
