<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'New journal entry',
);

if(sizeof($model->templates))
{
  $this->menutitle='Templates';
  $this->menu=array();

  foreach($model->templates as $template)
  {
    $title = Yii::t('delt', 'Use the template «{description}»', array('{description}'=>$template->description));
    
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
