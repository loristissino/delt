<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Closing entry',
);

$this->menu=array();

foreach($model->getMainPositions(true) as $mp)
{
  $this->menu[]=array('label'=>Yii::t('delt', 'Closing entry for accounts afferent to «{item}»', array('{item}'=>$mp->currentname)), 'url'=>array('bookkeeping/closingjournalentry', 'slug'=>$model->slug, 'position'=>$mp->position));
}

?>
<h1><?php echo Yii::t('delt', $this->journalentrydescription) ?></h1>

<?php if($position): ?>
<p><?php //echo Yii::t('delt', 'This firm does not seem to have accounts of «{position}» position to close.', array('{position}'=>Account::model()->getValidpositionByCode($position))) ?></p>
<?php else: ?>
<p><?php echo Yii::t('delt', 'Please choose the kind of closing you need on the side menu.') ?></p>
<?php endif ?>
