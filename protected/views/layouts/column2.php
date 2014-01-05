<?php /* @var $this Controller */ 

if($this->firm)
{
  $this->firmmenu=array(
    array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('/bookkeeping/coa', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Journal'), 'url'=>array('/bookkeeping/journal', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Trial Balance'), 'url'=>array('/bookkeeping/balance', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Statements'), 'url'=>array('/bookkeeping/statements', 'slug'=>$this->firm->slug, 'level'=>$this->firm->getCOAMaxLevel())),
    );
}
else
{
  $this->firmmenu=array();
  
  if($this->DEUser)
  {
    foreach($this->DEUser->firms as $firm)
    {
      $this->firmmenu[]=array('label'=>$firm, 'url'=>array('/bookkeeping/manage', 'slug'=>$firm->slug));
    }
  }
}

?>
<?php $this->beginContent('//layouts/main'); ?>


<div class="span-19">
  <div id="content">
  
  <?php if($this->firm): ?>
    <div id="firm_info">
      <h1><?php echo $this->firm->name ?></h1>
      <p><?php echo $this->createAbsoluteUrl('firm/view', array('slug'=>$this->firm->slug)) ?></p>
      <p><?php echo $this->firm->getOwners(true) ?></p>
    </div>
  <?php endif ?>

  <?php if(Yii::app()->user->hasFlash('delt_success')): ?>
    <div class="success">
    <?php echo Yii::t('delt', Yii::app()->user->getFlash('delt_success')) ?>
    </div>
  <?php endif ?>
  <?php if(Yii::app()->user->hasFlash('delt_failure')): ?>
    <div class="failure">
    <?php echo Yii::t('delt', Yii::app()->user->getFlash('delt_failure')) ?>
    </div>
  <?php endif ?>

    <?php echo $content; ?>
  </div><!-- content -->
</div>
<div class="span-5 last">
  <div id="sidebar">
  <?php if($this->firm): ?>

  <div class="portlet" id="yw3">
<div class="portlet-decoration firm">
<div class="portlet-title firm"><?php echo $this->firm->name ?></div>
</div>
</div>



  <?php endif ?>
  <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
      'title'=>Yii::t('delt', 'Bookkeeping/Accounting'),
    ));
    $this->widget('zii.widgets.CMenu', array(
      'items'=>$this->firmmenu,
      'htmlOptions'=>array('class'=>'operations'),
    ));
    $this->endWidget();
  ?>
  <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
      'title'=>Yii::t('delt', $this->menutitle),
    ));
    $this->widget('zii.widgets.CMenu', array(
      'items'=>$this->menu,
      'htmlOptions'=>array('class'=>'operations'),
    ));
    $this->endWidget();
  ?>
  <?php $this->widget('ext.widgets.delt.TimelinesWidget', array('timeline'=>$this->timeline)); ?>

  <?php
  /*
  $this->widget('ext.widgets.SocialShareWidget', array(
      'url' => isset($this->firm) ? $this->createAbsoluteUrl('/firm/public', array('slug'=>$this->firm->slug)) : $this->createAbsoluteUrl('/site/view'), 
      'services' => array('google', 'twitter', 'facebook'), 
      'htmlOptions' => array('class' => 'icons'), 
      'popup' => true,
  )) */?><span class="socialhint">&nbsp;<?php //echo Yii::t('delt', 'Spread the word!') ?></span> 
  
  </div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>
