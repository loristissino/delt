<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
);

$available_firms = $this->DEUser->profile->allowed_firms - sizeof($firms);

if($available_firms > 0)
{
  $this->menu=array(
    array('label'=>Yii::t('delt', 'Create firm'), 'url'=>array('firm/create')),
    array('label'=>Yii::t('delt', 'Fork an existing firm'), 'url'=>array('firm/fork')),
    );
}

?>
<h1><?php echo Yii::t('delt', 'Bookkeeping and Accounting') ?></h1>

<?php if(sizeof($firms)): ?>
  <p><?php echo Yii::t('delt', 'You can gain experience in bookkeping and accounting with the Double Entry method with your firms, listed on the right side.') ?></p>
<?php else: ?>
  <p>
    <?php echo Yii::t('delt', 'You have no firms that you can use to gain experience in bookkeeping/accounting with.') ?>
    <?php echo CHtml::link(Yii::t('delt', 'Create one.'), $this->createUrl('firm/create')) ?>
    <?php echo Yii::t('delt', 'Or, even better,') ?>
    <?php echo CHtml::link(Yii::t('delt', 'fork an existing one.'), $this->createUrl('firm/fork')) ?>
  </p>
<?php endif ?>

<?php if(sizeof($wfirms)>0): ?>
  <hr />
  <p><?php echo Yii::t('delt', 'You have been invited to manage the following firms:') ?></p>
  <ul>
    <?php foreach($wfirms as $firm): ?>
      <li><?php echo $firm->name ?>
      (<?php echo CHtml::link(Yii::t('delt', 'accept'), 
        $url = $this->createUrl('firm/invitation', array('slug'=>$firm->slug, 'action'=>'accept')), array(
          'submit'=>$url,
          'title'=>Yii::t('delt', 'Accept the invitation to share the management of this firm'),
          'confirm'=>Yii::t('delt', 'By accepting the invitation, you agree on the facts that: a) the contents of the firm are available under the Creative Commons Attribution-ShareAlike 3.0 Unported License, and b) your name will be listed as an author. Do you want to accept the invitation to share the management of this firm?'),
          ))
       ?> - <?php echo CHtml::link(Yii::t('delt', 'decline'), 
        $url = $this->createUrl('firm/invitation', array('slug'=>$firm->slug, 'action'=>'decline')), array(
          'submit'=>$url,
          'title'=>Yii::t('delt', 'Decline the invitation to share the management of this firm'),
          'confirm'=>Yii::t('delt', 'Are you sure you want to decline the invitation to share the management of this firm?'),
          ))
        ?>)</li>
    <?php endforeach ?>
  </ul>
<?php endif ?>

<?php if($available_firms <= 0): ?>
  <?php echo $this->renderPartial('/firm/_available') ?>
<?php endif ?>
