<form action="<?php echo $this->createUrl('section/togglevisibility', array('id'=>$section->id)) ?>" method="POST">
<?php if($section->is_visible): ?>
  <?php echo $this->createImageButton('visible', Yii::t('delt', 'Visible'), array('title'=>Yii::t('delt', 'This section is currently visible') . ' (' . Yii::t('delt', 'click to toggle') . ')', 'width'=>16, 'height'=>16)) ?>
<?php else: ?>
  <?php echo $this->createImageButton('invisible', Yii::t('delt', 'Invisible'), array('title'=>Yii::t('delt', 'This section is not currently visible') . ' (' . Yii::t('delt', 'click to toggle') . ')', 'width'=>16, 'height'=>16)) ?>
<?php endif ?>
</form>
