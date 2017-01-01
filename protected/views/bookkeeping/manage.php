<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Manage',
);

$this->menutitle=Yii::t('delt', 'Firm');

$this->menu=array();

if(!$model->frozen_at)
{
  $this->menu[] = array('label'=>Yii::t('delt', 'Edit Settings'), 'url'=>array('/firm/update', 'id'=>$model->id));
  $this->menu[] = array('label'=>Yii::t('delt', 'Delete'), 'url'=>array('/firm/delete', 'slug'=>$model->slug), 'linkOptions'=>array(
    'title'=>Yii::t('delt', 'Delete this firm'),
    ));
}

$this->menu[] = array('label'=>Yii::t('delt', 'Export'), 'url'=>array('/bookkeeping/export', 'slug'=>$model->slug));

if(!$model->frozen_at)
{
  $this->menu[] = array('label'=>Yii::t('delt', 'Import'), 'url'=>array('/bookkeeping/import', 'slug'=>$model->slug));
}

if(!$model->frozen_at)
{
  $this->menu[] = array('label'=>Yii::t('delt', 'Share'), 'url'=>array('/firm/share', 'slug'=>$model->slug));
}

$this->menu[] = array('label'=>Yii::t('delt', 
    $model->frozen_at ? 'Unfreeze' : 'Freeze'
    ), 'url'=>array('/firm/' . ($model->frozen_at ? 'unfreeze' : 'freeze'), 'slug'=>$model->slug));

$this->menu[]=array('label'=>Yii::t('delt', 'View Log'), 'url'=>array('/firm/log', 'slug'=>$this->firm->slug));

?>
<h1><?php echo CHtml::encode($model->name) ?></h1>

<p><?php echo nl2br(CHtml::encode($model->description)) ?></p>


<p>
<?php echo CHtml::link($this->createIcon('icons/coa', 'chart of accounts', array('width'=>120, 'height'=>120)), array('/bookkeeping/coa', 'slug'=>$model->slug), array('title'=>Yii::t('delt', 'View and edit the Chart of Accounts'))) ?>
<?php echo CHtml::link($this->createIcon('icons/journal', 'journal', array('width'=>120, 'height'=>120)), array('/bookkeeping/journal', 'slug'=>$model->slug), array('title'=>Yii::t('delt', 'View and edit the Journal Entries'))) ?>
<?php echo CHtml::link($this->createIcon('icons/balance', 'balance', array('width'=>120, 'height'=>120)), array('/bookkeeping/balance', 'slug'=>$model->slug), array('title'=>Yii::t('delt', 'View the Trial Balance and the Ledger'))) ?>
<?php echo CHtml::link($this->createIcon('icons/statements', 'statements', array('width'=>120, 'height'=>120)), array('/bookkeeping/statements', 'slug'=>$model->slug), array('title'=>Yii::t('delt', 'View the Statements'))) ?>
<?php echo CHtml::link($this->createIcon('icons/settings', 'settings', array('width'=>120, 'height'=>120)), array('/firm/update', 'id'=>$model->id), array('title'=>Yii::t('delt', 'Edit firm\'s settings'))) ?>
</p>

<?php if($model->frozen_at): ?>
  <?php echo $this->renderPartial('/firm/_frostiness', array('model'=>$model, 'warning'=>false)) ?>
<?php endif ?>

<div>
<?php echo $model->getLicenseCode($this) ?>
</div>
