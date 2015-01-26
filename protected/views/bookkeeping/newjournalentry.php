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
  if($template->automatic)
  {
    $this->menutitle .= ' ' . Yii::t('delt', '(automatic)');
  }
  
  $this->menu=array(
    array('label'=>Yii::t('delt', 'Delete'), 'url'=>$url=$this->createUrl('bookkeeping/deletetemplate', array('id'=>$template->id)), 'linkOptions'=>array(
      'submit'=>$url,
      'title'=>Yii::t('delt', 'Delete this template'),
      'confirm'=>Yii::t('delt', 'Are you sure you want to delete this template?'),
    )),
    );
  if($template->automatic)
  {
    $this->menu[] = 
      array('label'=>Yii::t('delt', 'Remove Automatic Status'), 'url'=>$url=$this->createUrl('bookkeeping/toggleautomaticstatus', array('id'=>$template->id, 'status'=>0)), 'linkOptions'=>array(
        'submit'=>$url,
        'title'=>Yii::t('delt', 'Remove Automatic Status for this template'),
      ));
  }
  else
  {
    $this->menu[] = 
      array('label'=>Yii::t('delt', 'Add Automatic Status'), 'url'=>$url=$this->createUrl('bookkeeping/toggleautomaticstatus', array('id'=>$template->id, 'status'=>1)), 'linkOptions'=>array(
        'submit'=>$url,
        'title'=>Yii::t('delt', 'Add Automatic Status for this template'),
      ));
  }
}
elseif(sizeof($model->templates))
{
  $this->menutitle='Templates';
  $this->menu=array();
  foreach($model->templates as $template)
  {
    $title = Yii::t('delt', 'Use the template «%description%»', array('%description%'=>$template->description));
    
    if($template->automatic)
    {
      $title .= ' ' . Yii::t('delt', '(automatic)');
    }
    $this->menu[]=array('label'=>$template->abbreviatedDescription(15, ' '), 'url'=>array('bookkeeping/journalentryfromtemplate', 'id'=>$template->id), 'linkOptions'=>array('title'=>$title));
  }
}

?>
<h1><?php echo Yii::t('delt', 'New journal entry') ?></h1>

<?php echo $this->renderPartial('_journalentryform', array('journalentryform'=>$journalentryform, 'items'=>$items)) ?>
