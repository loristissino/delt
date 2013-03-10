<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'Create firm'), 'url'=>array('firm/create')),
  );

?>
<h1><?php echo Yii::t('delt', 'Bookkeeping and accountancy') ?></h1>

<?php if(sizeof($firms)): ?>
  <p><?php echo Yii::t('delt', 'You can gain experience in bookkeping with the Double Entry method with your firms, listed on the right side.') ?></p>
<?php else: ?>
  <p><?php echo Yii::t('delt', 'You have no firms that you can use to gain experience in bookkeeping with. Go create one.') ?></p>
<?php endif ?>

