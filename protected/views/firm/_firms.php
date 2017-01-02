<h2><?php echo Yii::t('delt', $title) ?></h2>

<?php foreach($firms as $firm): ?>
  <p class="firm_info">
    <span class="name"><?php echo CHtml::link($firm, $this->createUrl($action, array('slug'=>$firm->slug)), array('title'=>Yii::t('delt', $message, array('{firm}'=>$firm->name)))) ?></span>
    <?php if($firm->status == Firm::STATUS_STALE): ?>
      <span class="warning" title="<?php echo Yii::t('delt', 'Since this firm is stale, it will be deleted in 30 days.') ?>">
        <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16)) ?>
      </span>
    <?php endif ?>
    <br />
    <?php if($firm->description): ?>
      <span class="description"><?php echo CHtml::encode($firm->description) ?></span>
    <?php else: ?>
      <span class="nodescription">(<?php echo Yii::t('delt', 'no description provided') ?>)</span>
    <?php endif ?>
    <?php if($firm->status == Firm::STATUS_SYSTEM): ?>
      (<?php echo CHtml::link(Yii::t('delt', 'COA'), array(Yii::app()->params['publicpages'][$firm->firmtype].$firm->slug.'/coa'), array('title'=>Yii::t('delt', 'Show Chart of Accounts'))) ?>)
    <?php endif ?>

  </p>
<?php endforeach ?>
</p>
