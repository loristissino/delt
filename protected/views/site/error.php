<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>

<?php if($code==418): ?>
<?php echo $this->createIcon('teapot', Yii::t('delt', 'Teapot'), array('width'=>200, 'height'=>158)) ?>
<?php endif ?>
