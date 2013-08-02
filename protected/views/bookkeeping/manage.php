<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping/Accounting'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Manage',
);

$this->menutitle=Yii::t('delt', 'Firm');
$this->menu=array(
	array('label'=>Yii::t('delt', 'Edit'), 'url'=>array('/firm/update', 'id'=>$model->id)),
	array('label'=>Yii::t('delt', 'Delete'), 'url'=>$url=$this->createUrl('/firm/delete', array('id'=>$model->id)), 'linkOptions'=>array(
    'submit'=>$url,
    'title'=>Yii::t('delt', 'Delete this firm'),
    'confirm'=>Yii::t('delt', 'Are you sure you want to delete this firm?'),
    )),
	array('label'=>Yii::t('delt', 'Export'), 'url'=>array('/bookkeeping/export', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Import'), 'url'=>array('/bookkeeping/import', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Show'), 'url'=>array('/firms/'.$model->slug)),
	array('label'=>Yii::t('delt', 'Share'), 'url'=>array('/firm/share', 'slug'=>$model->slug)),
);

?>
<h1><?php echo $model->name ?></h1>

<p><?php echo $model->description ?></p>

<div>
<?php echo $model->getLicenseCode($this) ?>
</div>

