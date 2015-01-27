<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Update journal entry',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'New journal entry'), 'url'=>array('bookkeeping/newjournalentry', 'slug'=>$model->slug)),
  array('label'=>Yii::t('delt', 'Create Template'), 'url'=>$this->createUrl('bookkeeping/createtemplatefromjournalentry', array('id'=>$journalentryform->journalentry->id)),'linkOptions'=>array(
    'title'=>Yii::t('delt', 'Create a Template based on this journal entry'),
    ))
  );

if(true) // TODO -- we might manage a 'is deletable' condition
{
  $this->menu[]= array('label'=>Yii::t('delt', 'Delete'), 'url'=>$url=$this->createUrl('bookkeeping/deletejournalentry', array('id'=>$journalentryform->journalentry->id)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete this entry'),
      'confirm' => Yii::t('delt', 'Are you sure you want to delete this journal entry?'),
    ),
  );
}

?>
<h1><?php echo Yii::t('delt', 'Edit journal entry') ?></h1>

<?php echo $this->renderPartial('_journalentryform', array('journalentryform'=>$journalentryform, 'items'=>$items)) ?>
