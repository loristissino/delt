<?php
/* @var $this AccountController */
/* @var $account Account */
/* @var $firm Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  $account->isHidden()?'Statements configuration':'Chart of accounts' => array($account->isHidden()?'/bookkeeping/configure':'/bookkeeping/coa', 'slug'=>$firm->slug),
  $account->isHidden()?'New item':'New account',
  
);

?>

<h1><?php echo Yii::t('delt', $account->isHidden()?'Create new item':'Create new account') ?></h1>

<?php if($parent): $children=$parent->getChildren() ?>
  <p><?php echo Yii::t('delt', 'You are creating an account as a child of «%account%».', array('%account%'=>$parent)) ?> 
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

<?php echo $this->renderPartial('_form', array('model'=>$account)); ?>
