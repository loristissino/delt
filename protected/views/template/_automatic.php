<form action="<?php echo $this->createUrl('bookkeeping/toggleautomaticstatus', array('id'=>$template->id)) ?>" method="POST">
<?php if($template->automatic): ?>
  <?php echo $this->createImageButton('automatic_on', Yii::t('delt', 'Automatic'), array('title'=>Yii::t('delt', 'This template is automatically applied when preparing the statements') . ' (' . Yii::t('delt', 'click to toggle') . ')', 'width'=>16, 'height'=>16)) ?>
<?php else: ?>
  <?php echo $this->createImageButton('automatic_off', Yii::t('delt', 'Not Automatic'), array('title'=>Yii::t('delt', 'This template is not used when preparing the statements') . ' (' . Yii::t('delt', 'click to toggle') . ')', 'width'=>16, 'height'=>16)) ?>
<?php endif ?>
</form>
