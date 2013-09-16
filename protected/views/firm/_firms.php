<h2><?php echo Yii::t('delt', $title) ?></h2>

<?php foreach($firms as $firm): ?>
  <p class="firm">
    <span class="name"><?php echo CHtml::link($firm, $this->createUrl($action, array('slug'=>$firm->slug)), array('title'=>Yii::t('delt', $message, array('{firm}'=>$firm->name)))) ?></span><br />
    <?php if($firm->description): ?>
      <span class="description"><?php echo CHtml::encode($firm->description) ?></span>
    <?php else: ?>
      <span class="nodescription">(<?php echo Yii::t('delt', 'no description provided') ?>)</span>
    <?php endif ?>
  </p>
<?php endforeach ?>
</p>
