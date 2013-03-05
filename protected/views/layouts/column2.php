<?php /* @var $this Controller */ ?>
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
