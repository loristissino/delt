<?php
/* @var $this FirmController */

$this->layout='//layouts/html5';
$this->pageTitle=Yii::app()->name . ' - ' . $model->name;
/*
$this->breadcrumbs=array(
  'Firms'=>array('/firm/index'),
  $model->name => array('/firm/public', 'slug'=>$model->slug),
  'Public',
);
*/
$this->css=$model->css;

$last_date = $model->getLastDate();

$toggle_text = addslashes(Yii::t('delt', 'Toggle the visibility of:'));
$toggle_description = addslashes(Yii::t('delt', 'firm\'s description'));
$toggle_excluded = addslashes(Yii::t('delt', 'excluded journal entries'));
$toggle_journal = addslashes(Yii::t('delt', 'journal'));
$toggle_statements = addslashes(Yii::t('delt', 'statements'));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'toggle-handler',
  '

var description_visible = true;
var excluded_visible = true;
var journal_visible = true;
var statements_visible = true;

$("#commands").html(
  "' . $toggle_text . '<br />" +
  [
  "<a href=\"#\" id=\"toggle_description\">' . $toggle_description . '</a>",
  "<a href=\"#\" id=\"toggle_excluded\">' . $toggle_excluded . '</a>",
  "<a href=\"#\" id=\"toggle_journal\">' . $toggle_journal . '</a>",
  "<a href=\"#\" id=\"toggle_statements\">' . $toggle_statements . '</a>",
  ]
  .join(" - "));

$("#toggle_description").click( function() {
  description_visible = !description_visible;
  $("#description").css("display", description_visible ? "block" : "none");
});

$("#toggle_excluded").click( function() {
  excluded_visible = !excluded_visible;
  $(".excluded").css("display", excluded_visible ? "table-row" : "none");
});

$("#toggle_journal").click( function() {
  journal_visible = !journal_visible;
  $("#journal").css("display", journal_visible ? "block" : "none");
});

$("#toggle_statements").click( function() {
  statements_visible = !statements_visible;
  $("#statements").css("display", statements_visible ? "block" : "none");
});

'
  ,
  CClientScript::POS_READY
);




?>

<article>
<h1><?php echo CHtml::encode($model->name) ?></h1>
<div id="description">
<p><?php echo nl2br(CHtml::encode($model->description)) ?></p>
<?php echo $this->renderPartial('_banner', array('firm'=>$model)) ?>
</div>
<section id="journal">
<h2><?php echo Yii::t('delt', 'Journal') ?></h2>

<table>
  <tr>
    <th><?php echo Yii::t('delt', 'No.') ?></th>
    <th><?php echo Yii::t('delt', 'Date') ?></th>
    <th><?php echo Yii::t('delt', 'Description') ?></th>
    <th><?php echo Yii::t('delt', 'Debit') ?></th>
    <th><?php echo Yii::t('delt', 'Credit') ?></th>
  </tr>
<?php $n=0; $journalentryid=0; $td=0; $tc=0; foreach($postings as $posting): $excluded=!$posting->journalentry->is_included ?>
  <?php if($journalentryid!=$posting->journalentry_id): $journalentryid=$posting->journalentry_id ?>
  <tr id="entry<?php echo ++$n ?>" <?php if($excluded) echo 'class="excluded"' ?>>
    <td class="firstjournalentryrow"><?php echo CHtml::link($n, '#entry'. $n, array('class'=>'hiddenlink')) ?></td>
    <td class="firstjournalentryrow">
      <?php echo Yii::app()->dateFormatter->formatDateTime($posting->journalentry->date, 'short', null) ?>
    </td>
    <td class="journaldescription firstjournalentryrow">
      <?php echo $posting->journalentry->description ?>
    </td>
    <td class="firstjournalentryrow"></td>
    <td class="firstjournalentryrow"></td>
  </tr>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
  <?php else: ?>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
  <?php endif ?>
<?php endforeach ?>
  <tr>
    <td class="firstjournalentryrow" colspan="2"></td>
    <td class="firstjournalentryrow">
      <?php echo Yii::t('delt', 'Total:') ?>
    </td>
    <td class="firstjournalentryrow currency lastjournalentryrow"><?php echo DELT::currency_value($td, $this->firm->currency) ?></td>
    <td class="firstjournalentryrow currency lastjournalentryrow"><?php echo DELT::currency_value($tc, $this->firm->currency) ?></td>
  </tr>
</table>
<hr />
</section>

<section id="statements">
<h2><?php echo Yii::t('delt', 'Statements') ?></h2>
<?php foreach($model->getMainPositions(false, array(1,2,3)) as $statement): ?>
  <?php echo $this->renderPartial('/bookkeeping/_statement', array(
    'statement'=>$statement,
    'data'=>$model->getStatement($statement, $level),
    'model'=>$model,
    'level'=>$level,
    'maxlevel'=>$maxlevel,
    'hlevel'=>3,
    'links'=>true,
    'last_date'=> $last_date,
    )) ?>
<?php endforeach ?>
<hr />
</section>  

<?php echo $this->renderPartial('_frostiness', array('model'=>$model, 'warning'=>false)) ?>
<p id="commands"></p>

<?php echo $model->getLicenseCode($this) ?>

</article>
