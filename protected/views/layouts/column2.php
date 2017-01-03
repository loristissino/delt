<?php /* @var $this Controller */ 

if($this->firm)
{
  $this->firmmenu=array(
    array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('/bookkeeping/coa', 'slug'=>$this->firm->slug)),
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
    foreach($this->DEUser->firms as $firm)
    {
      $label = $firm;
      $linkOptions = array();
      if ($firm->status == Firm::STATUS_STALE)
      {
        $linkOptions['style'] = 'color:red';
        $linkOptions['title'] = Yii::t('delt', 'This firm is stale');
      }
      $this->firmmenu[]=array('label'=>$label, 'url'=>array('/bookkeeping/manage', 'slug'=>$firm->slug), 'linkOptions'=>$linkOptions);
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

    <?php $this->renderPartial('/layouts/_flashes'); ?>

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
  <?php /*twitter timeline*/ // $this->widget('ext.widgets.delt.TimelinesWidget', array('timeline'=>$this->timeline)); ?>
  
  <h2>Twitter feed</h2>
  <p><?php echo Yii::t('delt', 'This is where our <a href="{url}">twitter feed</a> should be placed.', array('{url}'=>Yii::app()->params['twitterFeed'])) ?>
  <br />
  <?php echo Yii::t('delt', 'Until we find out how to be sure to respect the cookie law, you won\'t see it directly.') ?><br />
  <?php echo Yii::t('delt', 'Click on the link above to access it.') ?>
  
  </p>

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
