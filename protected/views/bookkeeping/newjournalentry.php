<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'New journal entry',
);

if($template)
{
  $this->menutitle=Yii::t('delt', 'Template «%description%»', array('%description%'=>$template->description));
  $this->menu=array(
    array('label'=>Yii::t('delt', 'Delete'), 'url'=>$url=$this->createUrl('bookkeeping/deletetemplate', array('id'=>$template->id)), 'linkOptions'=>array(
      'submit'=>$url,
      'title'=>Yii::t('delt', 'Delete this template'),
      'confirm'=>Yii::t('delt', 'Are you sure you want to delete this template?'),
    )),
    );
}
elseif(sizeof($model->templates))
{
  $this->menutitle='Templates';
  $this->menu=array();
  foreach($model->templates as $template)
  {
    $this->menu[]=array('label'=>$template->description, 'url'=>array('bookkeeping/journalentryfromtemplate', 'id'=>$template->id));
  }
}

?>
<h1><?php echo Yii::t('delt', 'New journal entry') ?></h1>

<?php echo $this->renderPartial('_journalentryform', array('journalentryform'=>$journalentryform, 'items'=>$items)) ?>
