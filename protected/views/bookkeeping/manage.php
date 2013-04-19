<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
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
	array('label'=>Yii::t('delt', 'View as published'), 'url'=>array('/firm/public', 'slug'=>$model->slug)),
);

?>
<h1><?php echo $model->name ?></h1>

<p><?php echo $model->description ?></p>

<div>
<?php echo $model->getLicenseCode($this) ?>
</div>

<?php /*$this->widget('zii.widgets.CMenu', array(
    'items'=>array(
        array('label'=>Yii::t('delt','Bookkeeping'), 'items'=>array(
            array('label'=>Yii::t('delt','Chart of accounts'), 'url'=>array('/bookkeeping/coa', 'slug'=>$model->slug)),
            array('label'=>Yii::t('delt','Journal'), 'url'=>array('/bookkeeping/journal', 'slug'=>$model->slug)),
            array('label'=>Yii::t('delt','Trial balance'), 'url'=>array('/bookkeeping/balance', 'slug'=>$model->slug)),
        )),
        array('label'=>Yii::t('delt','Management'), 'items'=>array(
            array('label'=>Yii::t('delt','Edit / Update'), 'url'=>array('/firm/update', 'slug'=>$model->slug)),
            array('label'=>Yii::t('delt','Export'), 'url'=>array('/firm/export', 'slug'=>$model->slug)),
        ))
     ),
));*/
?>
