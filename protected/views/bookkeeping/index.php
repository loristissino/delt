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

<p>
<?php if(sizeof($firms)): ?>
  <?php echo CHtml::link($this->createIcon('icons/manage', 'manage', array('width'=>120, 'height'=>120)), array('/bookkeeping/index', 'list'=>'on'), array('title'=>Yii::t('delt', 'Manage one of your firms'))) ?>
<?php endif ?>
<?php if($available_firms > 0): ?>
  <?php echo CHtml::link($this->createIcon('icons/create', 'create', array('width'=>120, 'height'=>120)), array('/firm/create'), array('title'=>Yii::t('delt', 'Create a new, empty firm'))) ?>
  <?php echo CHtml::link($this->createIcon('icons/fork', 'fork', array('width'=>120, 'height'=>120)), array('/firm/fork'), array('title'=>Yii::t('delt', 'Fork (duplicate) an existing firm'))) ?>
<?php endif ?>
  <?php echo CHtml::link($this->createIcon('icons/profile', 'profile', array('width'=>120, 'height'=>120)), array('/user/profile/edit'), array('title'=>Yii::t('delt', 'Edit your profile\'s settings'))) ?>
</p>

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
          'confirm'=>Yii::t('delt', "By accepting the invitation, you agree on the following terms:\n\na) the contents of the firm are available under the Creative Commons Attribution-ShareAlike 3.0 Unported License;\n\nb) your name will be listed as an author.\n\nDo you want to accept the invitation to share the management of this firm?"),
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
