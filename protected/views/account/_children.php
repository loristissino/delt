<?php if($parent): $children=$parent->getChildren() ?>
  <p>
  <?php if($type=='create'): ?>
    <?php echo Yii::t('delt', 'You are creating an account as a child of «%account%».', array('%account%'=>$parent)) ?> 
  <?php endif ?>
  <?php if($type=='update'): ?>
    <?php echo Yii::t('delt', 'You are making the account a child of «%account%».', array('%account%'=>$parent)) ?> 
  <?php endif ?>
  <?php if(sizeof($children)): ?>
    <?php echo Yii::t('delt', 'For your reference, this is the list of its current children:') ?></p>
    <ul>
    <?php foreach($children as $child): ?> 
      <li><?php echo $child ?>  </li>
    <?php endforeach ?>
    </ul>
  <?php else: ?>
    <?php echo Yii::t('delt', 'It does not have any children at the moment.') ?></p>
  <?php endif ?>
<?php endif ?>
