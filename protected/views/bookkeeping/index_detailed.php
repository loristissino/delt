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

<?php echo $this->renderPartial('../firm/_firms', array('title'=>'Manage one of your firms', 'firms'=>$firms, 'action'=>'bookkeeping/manage', 'message'=>'Manage the firm «{firm}»')) ?>


