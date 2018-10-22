<?php /* @var $this Controller */ 

if($this->firm)
{
  $this->firmmenu=array(
    array('label'=>Yii::t('delt', 'Chart of Accounts'), 'url'=>array('/bookkeeping/coa', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Journal'), 'url'=>array('/bookkeeping/journal', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'General Ledger'), 'url'=>array('/bookkeeping/generalledger', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Trial Balance'), 'url'=>array('/bookkeeping/balance', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Statements'), 'url'=>array('/bookkeeping/statements', 'slug'=>$this->firm->slug, 'level'=>$this->firm->getCOAMaxLevel())),
    array('label'=>Yii::t('delt', 'Public View'), 'url'=>array(Yii::app()->params['publicpages'][$this->firm->firmtype].$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Slideshow'), 'url'=>array(Yii::app()->params['publicpages'][$this->firm->firmtype].$this->firm->slug.'/slideshow')),
    array('label'=>Yii::t('delt', 'Management'), 'url'=>array('/bookkeeping/manage', 'slug'=>$this->firm->slug)),
    );
}
else
{
  $this->firmmenu=array();
  
  if($this->DEUser)
  {
    $max_shown = 5;
    foreach($this->DEUser->firms as $firm)
    {
      $label = $firm;
      $linkOptions = array();
      if ($firm->status == Firm::STATUS_STALE)
      {
        $linkOptions['style'] = 'color:red';
        $linkOptions['title'] = Yii::t('delt', 'This firm is stale');
      }
      if (sizeof($this->firmmenu)<$max_shown)
      {
        $this->firmmenu[]=array('label'=>$label, 'url'=>array('/bookkeeping/manage', 'slug'=>$firm->slug), 'linkOptions'=>$linkOptions);
      }
      else
      {
        break;
      }
    }
    if (sizeof($this->DEUser->firms)>$max_shown)
    {
      $this->firmmenu[]=array('label'=>Yii::t('delt', 'Older firms…'), 'url'=>array('/bookkeeping/index', 'list'=>'on'), 'linkOptions'=>array('style'=>'font-style:italic'));
    }
    
  }
}

?>
<?php $this->beginContent('//layouts/main'); ?>

<div class="span-19">
  <div id="content">
    <?php 
      $this->widget('application.components.widgets.CChallenge', array(
        'id'=>'challenge',
        'hideOnEmpty'=>true,
      ));
    ?>
    <?php if($this->firm): ?>
      <div id="firm_info">
        <h1><?php echo $this->firm->name ?></h1>
        <p><?php echo $this->createAbsoluteUrl('firm/view', array('slug'=>$this->firm->slug)) ?></p>
        <p><?php echo $this->firm->getOwners(true) ?></p>
      </div>
    <?php endif ?>

    <?php $this->renderPartial('//layouts/_flashes'); ?>

    <?php echo $content; ?>
  </div><!-- content -->
</div>
<div class="span-5 last">
  <div id="sidebar">
  <?php if($this->firm): ?>

<div class="portlet-decoration firm">
  <div class="portlet-title firm"><?php echo $this->firm->name ?></div>
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
  
  <?php if(!$this->firm && isset(Yii::app()->params['sideText'])) include_once(Yii::app()->params['sideText']) ?>

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
