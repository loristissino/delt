<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Chart of accounts' => array('/bookkeeping/accountschart', 'slug'=>$model->slug),
  'Fix',
);

?>
<h1><?php echo Yii::t('delt', 'Fix chart of accounts') ?></h1>

<p>
<?php echo Yii::t('delt', 'This operation will perform some consistency checks on the chart of accounts, fixing the problems when possible.') ?>
&nbsp;
<?php echo Yii::t('delt', 'It is not destructive, and can be safely run.') ?>
</p>

<p>
<?php echo Yii::t('delt', 'Examples of possible problems include:') ?>
</p>
<ul>
  <li><?php echo Yii::t('delt', 'changes in the code or in the nature of an account, that must be propagated to the children') ?></li>
  <li><?php echo Yii::t('delt', 'insertion of an account as a child of another account, that must be therefore be marked as unselectable') ?></li>
  <li><?php echo Yii::t('delt', 'deletion of all the children of an account, that must be therefore marked as selectable') ?></li>
</ul>

</p>
<?php $form=$this->beginWidget('CActiveForm'); ?>
    <div class="row submit">
        <?php echo CHtml::submitButton(Yii::t('delt', 'Fix my chart of accounts')) ?>
    </div>
<?php $this->endWidget(); ?>
