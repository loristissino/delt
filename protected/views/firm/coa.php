<?php
/* @var $this FirmController */

$this->layout='//layouts/html5';
/*
$this->breadcrumbs=array(
	'Firms'=>array('/firm/index'),
	$model->name => array('/firm/public', 'slug'=>$model->slug),
  'Public',
);
*/

?>

<article>
<h1><?php echo CHtml::encode($model->name) ?></h1>
<p><?php echo CHtml::encode($model->description) ?></p>
<section>
<h2><?php echo Yii::t('delt', 'Chart of Accounts') ?></h2>
<?php foreach($accounts as $account): ?>
  <div style="padding-left: <?php echo 10*$account->level - 10 ?>px">
    <?php echo $account->code ?><br/>
    <div style="padding-left: 10px">
    <strong><?php echo nl2br($account->textnames) ?></strong>
    <?php echo $this->renderPartial('../account/_clevercomment', array('account'=>$account), true) ?>
    <?php echo Yii::t('delt', 'Position') ?>: <?php echo $this->renderPartial('../account/_position',array('account'=>$account),true) ?><br /> 
    <?php echo Yii::t('delt', 'Ordinary outstanding balance') ?>: 
    
    <?php if($account->outstanding_balance===null and $account->is_selectable) echo '/'; else switch($account->outstanding_balance){
      case 'D': echo Yii::t('delt', 'Dr.<!-- outstanding balance -->'); break;
      case 'C': echo Yii::t('delt', 'Cr.<!-- outstanding balance -->'); break;
    }?>
    </div>
    <br />
  </div>
<?php endforeach ?>
</section>
</article>
