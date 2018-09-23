<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $this->firm->name => array('/bookkeeping/manage', 'slug'=>$this->firm->slug),
  'Sections' => array('/section/admin', 'slug'=>$this->firm->slug),
  $model->name
);

$this->menu=array(
	array('label'=>'Manage Sections', 'url'=>array('section/admin', 'slug'=>$firm->slug)),
	array('label'=>'Create Section', 'url'=>array('create', 'slug'=>$this->firm->slug)),
	array('label'=>'Delete Section', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1><?php echo Yii::t('delt', 'Section') ?> «<?php echo $model->name; ?>»</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
    array(
      'label'=>Yii::t('delt', 'Visible?'),
      'type'=>'raw',
      'value'=>$model->is_visible ? Yii::t('delt', 'yes'): Yii::t('delt', 'No'),
        ),
		'rank',
	),
)); ?>

<br />


<?php
  echo $this->renderPartial('//firm/_journal', array('postings'=>$postings, 'model'=>$this->firm, 'title'=>Yii::t('delt', 'Journal entries'), 'linked'=>false, 'editjournalentry'=>true));
?>


