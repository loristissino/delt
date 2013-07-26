<?php
/* @var $this AccountController */
/* @var $firm Firm */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  'Chart of accounts' => array('/bookkeeping/coa', 'slug'=>$firm->slug),
  'Synchronize',
);

if(isset($ancestor))
{
  $newitems=array();
  $changeditems = array();
  
  if(sizeof($diff['new']))
  {
    $newitems=array();
    array_map(function($account) use (&$newitems)
    {
      $newitems[$account->id]='<td>' . implode('</td><td>', 
        array(
          $account->code, 
          nl2br($account->textnames), 
          nl2br($account->comment),
          Yii::t('delt', $account->position),
          Yii::t('delt', $account->outstanding_balance),
        )
      ) . '</td>';
    },
    $diff['new']
    );
  }
  
  
  if(sizeof($diff['changes']))
  {
    array_map(function($value) use (&$changeditems)
    {
      $changes=array();
      foreach($value['differences'] as $key=>$difference)
      {
        if(strpos($difference['source'], "\n") or strpos($difference['target'], "\n"))
        {
          $changes[]='<span title="' . Yii::t('delt', "----\n{source}\n---\nvs\n----\n{target}\n----", array('{source}'=>$difference['source'], '{target}'=>$difference['target'])) . '">' . Yii::t('delt', $key). '</span>';
        }
        else
        {
          $changes[]='<span title="' . Yii::t('delt', '«{source}» vs «{target}»', array('{source}'=>$difference['source'], '{target}'=>$difference['target'])) . '">' . Yii::t('delt', $key). '</span>';
        }
        
      }
      $changeditems[$value['account']->id]='<td>' . implode('</td><td>', 
        array(
          $value['account']->code,
          $value['account']->name,
          implode(', ', $changes), 
        )
      ) . '</td>';
    },
    $diff['changes']
    );
    
  }
  
}

?>

<h1><?php echo Yii::t('delt', 'Synchronize accounts') ?></h1>


<?php if(isset($ancestors)): ?>
<p>
  <?php echo Yii::t('delt', 'You can synchronize your firm\'s chart of accounts with the one of one of the following ancestors:') ?>
</p>
<ul>
  <?php foreach($ancestors as $anc): ?>
  <li><?php echo CHtml::link($anc->name, array('/account/synchronize', 'slug'=>$firm->slug, 'ancestor'=>$anc->slug)) ?></li>
  <?php endforeach ?>
</ul>
<?php endif ?>

<?php if(isset($ancestor) and (sizeof($newitems) or sizeof($changeditems))): ?>
  
  <div class="form">

  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'accounts-form',
    'enableAjaxValidation'=>false,
  )); ?>

  <?php if(sizeof($newitems)): ?>
    <h3><?php echo Yii::t('delt', 'New accounts found') ?></h3>
    <table class="syncaccountstable">
      <tr>
        <th class="first"></th>
        <th><?php echo Yii::t('delt', 'Code') ?></th>
        <th><?php echo Yii::t('delt', 'Name') ?></th>
        <th><?php echo Yii::t('delt', 'Comment') ?></th>
        <th><?php echo Yii::t('delt', 'position') ?></th>
        <th><?php echo Yii::t('delt', 'Outstanding balance') ?></th>
      </tr>
    <?php echo CHtml::checkBoxList(
      'newaccounts',
      array(),
      $newitems,
      array(
        'separator'=>'',
        'template'=>'<tr><td>{input}</td>{label}</tr>',
        'checkAll'=>'<td colspan="5" style="background: white">' . Yii::t('delt', 'Select all') . '</td>',
        'checkAllLast'=>true,
        'container'=>'',
        )
      );
    ?>
  </table>
  <?php endif ?>

  <?php if(sizeof($changeditems)): ?>
    <h3><?php echo Yii::t('delt', 'Changed accounts found') ?></h3>
    <table class="syncaccountstable">
      <tr>
        <th class="first"></th>
        <th><?php echo Yii::t('delt', 'Code') ?></th>
        <th><?php echo Yii::t('delt', 'Name') ?></th>
        <th><?php echo Yii::t('delt', 'Changes') ?></th>
      </tr>
    <?php echo CHtml::checkBoxList(
      'changedaccounts',
      array(),
      $changeditems,
      array(
        'separator'=>'',
        'template'=>'<tr><td>{input}</td>{label}</tr>',
        'checkAll'=>'<td colspan="4" style="background: white">' . Yii::t('delt', 'Select all') . '</td>',
        'checkAllLast'=>true,
        'container'=>'',
        )
      );
    ?>
  </table>
  <?php endif ?>
  
  <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('delt', 'Synchronize')); ?>
	</div>

  <?php $this->endWidget(); ?>
  </div>

<?php else: ?>
  <p><?php echo Yii::t('delt', 'There are no accounts to synchronize.') ?></p>
<?php endif ?>

