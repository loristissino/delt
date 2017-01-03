<?php /* @var $this Controller */ 

if($this->firm)
{
  $this->firmmenu=array(
    array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('/bookkeeping/coa', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Journal'), 'url'=>array('/bookkeeping/journal', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'General Ledger'), 'url'=>array('/bookkeeping/generalledger', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Trial Balance'), 'url'=>array('/bookkeeping/balance', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Statements'), 'url'=>array('/bookkeeping/statements', 'slug'=>$this->firm->slug, 'level'=>$this->firm->getCOAMaxLevel())),
    );
}
else
{
  $this->firmmenu=array();

}

?>
<?php $this->beginContent('//layouts/main'); ?>

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

<div class="menu_below">
  <div id="sidebar">
  <?php if($this->firm): ?>
      <div class="portlet-decoration firm">
        <div class="portlet-title firm"><?php echo $this->firm->name ?></div>
      </div>
  <?php endif ?>
  <div>
  <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
      'title'=>Yii::t('delt', 'Bookkeeping/Accounting'),
    ));
    $this->widget('zii.widgets.CMenu', array(
      'items'=>$this->firmmenu,
      'htmlOptions'=>array('class'=>'operations below'),
    ));
    $this->endWidget();
  ?>
  </div>
  <div>
  <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
      'title'=>Yii::t('delt', $this->menutitle),
    ));
    $this->widget('zii.widgets.CMenu', array(
      'items'=>$this->menu,
      'htmlOptions'=>array('class'=>'operations below'),
    ));
    $this->endWidget();
  ?>
  </div>
  <div class="span-6">
  <?php /*twitter timeline*/ $this->widget('ext.widgets.delt.TimelinesWidget', array('timeline'=>$this->timeline)); ?>

  <?php
  /*
  $this->widget('ext.widgets.SocialShareWidget', array(
      'url' => isset($this->firm) ? $this->createAbsoluteUrl('/firm/public', array('slug'=>$this->firm->slug)) : $this->createAbsoluteUrl('/site/view'), 
      'services' => array('google', 'twitter', 'facebook'), 
      'htmlOptions' => array('class' => 'icons'), 
      'popup' => true,
  )) */?><span class="socialhint">&nbsp;<?php //echo Yii::t('delt', 'Spread the word!') ?></span> 
  
  </div>

  </div><!-- content -->
</div>

</div>
<?php $this->endContent(); ?>

