<?php /* @var $this Controller */ 

if($this->firm)
{
  $this->firmmenu=array(
    array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('bookkeeping/coa', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Journal'), 'url'=>array('bookkeeping/journal', 'slug'=>$this->firm->slug)),
    array('label'=>Yii::t('delt', 'Trial Balance'), 'url'=>array('bookkeeping/balance', 'slug'=>$this->firm->slug)),
    );
}

?>
<?php $this->beginContent('//layouts/main'); ?>


<div class="span-19">
	<div id="content">

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
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>Yii::t('delt', 'Bookkeeping'),
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->firmmenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>Yii::t('delt', 'Operations'),
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>
