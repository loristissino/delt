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
$toggle_challenge = addslashes(Yii::t('delt', 'challenge'));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'toggle-handler',
  '

var description_visible = true;
var excluded_visible = true;
var journal_visible = true;
var statements_visible = true;
var challenge_visible = true;

$("#commands").html(
  "' . $toggle_text . '<br />" +
  [
  "<a href=\"#\" id=\"toggle_description\">' . $toggle_description . '</a>",
  "<a href=\"#\" id=\"toggle_excluded\">' . $toggle_excluded . '</a>",
  "<a href=\"#\" id=\"toggle_journal\">' . $toggle_journal . '</a>",
  "<a href=\"#\" id=\"toggle_statements\">' . $toggle_statements . '</a>",
  "<a href=\"#\" id=\"toggle_challenge\">' . $toggle_challenge . '</a>",
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

$("#toggle_challenge").click( function() {
  challenge_visible = !challenge_visible;
  $("#challenge").css("display", challenge_visible ? "block" : "none");
});

'
  ,
  CClientScript::POS_READY
);


if (sizeof($results))
{
  $md = new CMarkdown();
}

?>

<article>
<h1><?php echo CHtml::encode($model->name) ?></h1>
<div id="description">
<p><?php echo nl2br(CHtml::encode($model->description)) ?></p>
<?php echo $this->renderPartial('_banner', array('firm'=>$model)) ?>
</div>

<?php
  echo $this->renderPartial('_journal', array('postings'=>$postings, 'model'=>$model, 'title'=>Yii::t('delt', 'Journal'), 'linked'=>true, 'editjournalentry'=>false));
?>

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

<?php if(sizeof($results)): ?>
  <section id="challenge">
    
  <h2><?php echo Yii::t('delt', 'Challenge') ?></h2>
    <?php foreach(DELT::getValueFromArray($results, 'transactions', array()) as $result): ?>
      <?php echo Yii::app()->controller->renderPartial('/challenge/_info', array('source'=>$result, 'md'=>$md)) ?>
      <?php if ($result['checked'] || sizeof($result['errors'])): ?>
        <?php echo Yii::app()->controller->renderPartial('/challenge/_checks', array('source'=>$result, 'with_oks'=>true, 'with_points'=>true)) ?>
      <?php endif ?>
    <?php endforeach ?>
    
  </section>
  <hr />
<?php endif ?>

<?php echo $this->renderPartial('_frostiness', array('model'=>$model, 'warning'=>false)) ?>
<p id="commands"></p>

<?php echo $model->getLicenseCode($this) ?>

</article>
