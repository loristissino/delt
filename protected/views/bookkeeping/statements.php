<?php
/* @var $this BookkeepingController */

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

<div id="automatic_entriesd">
<h2><?php echo Yii::t('delt', 'Automatic Entries') ?></h2>
<table>
  <tr>
    <th colspan="2"><?php echo Yii::t('delt', 'Description') ?></th>
    <th><?php echo Yii::t('delt', 'Debit') ?></th>
    <th><?php echo Yii::t('delt', 'Credit') ?></th>
  </tr>
<?php foreach($automatic_entries as $je): ?>
  <tr>
    <td colspan="2" class="description <?php echo $je['journalentry']['class'] ?>"><?php echo $je['journalentry']['description'] ?></td>
    <td colspan="2"></td>
  </tr>
    <?php foreach($je['postings'] as $posting): ?>
    <tr>
      <td>&nbsp;&nbsp;&nbsp;</td>
      <td><div class="<?php echo $posting['amount']>0? 'jdebit': 'jcredit' ?>"><?php echo CHtml::link($posting['account_name'], array('bookkeeping/ledger', 'id'=>$posting['account_id'])) ?></div></td>
      <?php echo $this->renderPartial('../firm/_td_debit_amount', array('amount'=>$posting['amount'])) ?>
      <?php echo $this->renderPartial('../firm/_td_credit_amount', array('amount'=>$posting['amount'])) ?>
    </tr>
    <?php endforeach ?>
<?php endforeach ?>
</table>

</div>
