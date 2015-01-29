<?php
/* @var $this BookkeepingController */

$show_text = Yii::t('delt', 'Show automatically-generated entries');
$hide_text = Yii::t('delt', 'Hide automatically-generated entries');

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'toggle-automatic-entries',
  '
  
  $("#automatic_entries").hide();
  var entries_shown=false;

  $("#toggle").click(function() {
    $("#automatic_entries").toggle(500);
    if(entries_shown)
    {
      entries_shown=false;
      $("#toggle").html("' . $show_text . '");
      console.log("hide");
    }
    else
    {
      entries_shown = true;
      $("#toggle").html("' . $hide_text . '");
      console.log("show ");
    }
  });
  
  '
  
  ,
  CClientScript::POS_READY
);




$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Statements',
);

$this->menutitle=Yii::t('delt', 'Depth');

$this->menu=array();

for($i=1; $i<=$model->COAMaxLevel; $i++)
{
  $this->menu[]=array('label'=>Yii::t('delt', 'Down to Level {number}', array('{number}'=>$i)), 'url'=>array('bookkeeping/statements', 'slug'=>$model->slug, 'level'=>$i));
}

$last_date = $model->getLastDate();

?>
<h1><?php echo Yii::t('delt', 'Statements') ?></h1>
<div id="statements">
<?php foreach($model->getMainPositions(false, array(1,2,3)) as $statement): ?>

  <?php echo $this->renderPartial('_statement', array(
    'statement'=>$statement,
    'data'=>$model->getStatement($statement, $level),
    'model'=>$model,
    'level'=>$level,
    'maxlevel'=>$maxlevel,
    'hlevel'=>2,
    'links'=>true,
    'last_date'=> $last_date,
    )) ?>
    
<?php endforeach ?>

</div>

<p><a id="toggle" href="#automatic_entries"><?php echo $show_text ?></a></p>

<div id="automatic_entries">
<h2><?php echo Yii::t('delt', 'Automatic Entries') ?></h2>

<p>
  <?php echo Yii::t('delt', 'The following journal entries, if present, have been automatically applied in order to prepare the financial statement above.') ?>
  <?php echo Yii::t('delt', 'They are deleted just afterwards, so you will not see them in the journal or in the general ledger') ?>
</p>

<table>
  <tr>
    <th colspan="2"><?php echo Yii::t('delt', 'Description') ?></th>
    <th><?php echo Yii::t('delt', 'Debit') ?></th>
    <th><?php echo Yii::t('delt', 'Credit') ?></th>
  </tr>
<?php foreach($automatic_entries as $je): ?>
  <tr class="descriptionline">
    <td colspan="2" class="description <?php echo $je['journalentry']['class'] ?>"><?php echo CHtml::encode($je['journalentry']['description']) ?></td>
    <td colspan="2"></td>
  </tr>
    <?php foreach($je['postings'] as $posting): ?>
    <tr>
      <td>&nbsp;&nbsp;&nbsp;</td>
      <td><div class="<?php echo $posting['amount']>0? 'jdebit': 'jcredit' ?>"><?php echo CHtml::encode($posting['account_name']) ?></div></td>
      <?php echo $this->renderPartial('../firm/_td_debit_amount', array('amount'=>$posting['amount'])) ?>
      <?php echo $this->renderPartial('../firm/_td_credit_amount', array('amount'=>$posting['amount'])) ?>
    </tr>
    <?php endforeach ?>
    <?php if(sizeof($je['postings'])==0): ?>
      <?php foreach($je['accounts'] as $posting): $posting['amount'] = $posting['debit'] - $posting['credit']; $posting['account_name']=$posting['name']; $posting['account_id']=$posting['id'] ?>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td class="<?php echo $je['journalentry']['class'] ?>"><div class="<?php echo $posting['amount']>0? 'jdebit': 'jcredit' ?>"><?php echo $posting['account_name'] ? CHtml::encode($posting['account_name']): Yii::t('delt', 'Closing account not found') ?></div></td>
        <?php echo $this->renderPartial('../firm/_td_debit_amount', array('amount'=>$posting['amount'], 'extraclasses'=>'excluded')) ?>
        <?php echo $this->renderPartial('../firm/_td_credit_amount', array('amount'=>$posting['amount'], 'extraclasses'=>'excluded')) ?>
      </tr>
      <?php endforeach ?>
    <?php endif ?>
<?php endforeach ?>
</table>

</div>
