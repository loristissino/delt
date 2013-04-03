<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
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
<h1><?php echo Yii::t('delt', 'Bookkeeping and accountancy') ?></h1>

<?php if(sizeof($firms)): ?>
  <p><?php echo Yii::t('delt', 'You can gain experience in bookkeping with the Double Entry method with your firms, listed on the right side.') ?></p>
<?php else: ?>
  <p><?php echo Yii::t('delt', 'You have no firms that you can use to gain experience in bookkeeping with. Go create one.') ?></p>
<?php endif ?>

<?php if($available_firms <= 0): ?>
  <?php echo $this->renderPartial('/firm/_available') ?>
<?php endif ?>
