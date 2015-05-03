<?php

$md = new CMarkdown();
$challenge = $this->getChallenge();

if($challenge)
{
  $params = CJavaScript::encode(array(
    'transaction'=>$challenge->transaction_id,
    'i18n'=>array(
      'toggle_context'=>Yii::t('delt', 'Context'),
      'toggle_firm'=>Yii::t('delt', 'Firm'),
      'toggle_transactions'=>Yii::t('delt', 'Transactions'),
      'icon_title_toggles'=>Yii::t('delt', 'Toggle visibility of items...'),
      ),
    ));

  $cs = Yii::app()->getClientScript();  
  $cs->registerScript(
    'challenge-config',
      'var params = ' .$params. ';
      execute(params);'
    ,
    CClientScript::POS_READY
  );

  $cs->registerScriptFile(
    Yii::app()->request->baseUrl.'/js/delt/challenge.js',
    CClientScript::POS_HEAD
  );

$challenge_visibility = Yii::app()->controller->challenge_visibility;

$challenge_firm_set = !!$challenge->firm_id;
// there is a firm set for the challenge

$challenge_firm_current = isset(Yii::app()->controller->firm) && $challenge->firm_id == Yii::app()->controller->firm->id;
// the current firm is the same of the challenge

$is_ajax = Yii::app()->controller->is_ajax;

}


?>
<?php if($challenge && $challenge_visibility!='none'): ?>

  <div id="challenge" lang="<?php echo $challenge->exercise->firm->language->locale ?>">

  <?php echo CHtml::script('params = ' . $params . '; execute(params);') 
  // we need to call execute() directly, to handle ajax updates reactions ?>
  <h2>
    <?php echo Yii::app()->controller->createIcon('book_open', Yii::t('delt', 'Open Challenge'), array('id'=>'challenge_icon', 'width'=>16, 'height'=>16, 'style'=>'cursor: pointer', 'title'=>Yii::t('delt', 'The running challenge'))); ?>
    <span id="challenge_commands" class="challengeinfo"></span>
    <?php echo CHtml::encode($challenge->exercise->title) ?>
    <?php if($challenge->method & Challenge::SHOW_POINTS_DURING_CHALLENGE): ?>
    <span class="challengeinfo"> - 
      <span class="score">
        <?php echo Yii::t('delt', 'Current score:') ?>
        <?php echo Yii::t('delt', 'One point|{n} points', $challenge->score) ?></span>
    <?php endif ?>
    </span>
  </h2>

  <div id="challenge_firm" style="display: <?php echo !($challenge_firm_set && $challenge_firm_current)&&(!$is_ajax) ? 'visible': 'none' ?>">
    <h3><?php echo Yii::t('delt', 'Firm') ?></h3>
  <p>
  <?php if($challenge_firm_set): ?>
    <?php echo Yii::t('delt', 'Associated firm:') ?> <?php echo $challenge->firm ?>
    <?php if(!$challenge_firm_current && Yii::app()->controller->firm): ?>
      <?php echo CHtml::ajaxLink(
        Yii::t('delt', 'Link the firm «%name%» instead', array('%name%'=>Yii::app()->controller->firm)),
        $url=CHtml::normalizeUrl(array('challenge/connect', 'id'=>$challenge->id, 'slug'=>Yii::app()->controller->firm->slug)),
        array(
          'replace' => '#challenge',
          'type' => 'POST',
          ),
        array(
          'title' => Yii::t('delt', 'Connect this firm with the active challenge'),
          )
        )
        ?>
      <?php else: ?>
        <?php if (!in_array(Yii::app()->controller->action->id, array('manage', 'activatetransaction'))): ?>
          <?php echo CHtml::link(
            Yii::t('delt', 'Manage'),
            array('bookkeeping/manage', 'slug'=>$challenge->firm->slug)
            )
            ?>
       <?php endif ?>
    <?php endif ?>
  <?php else: ?>
    <?php if(Yii::app()->controller->firm): ?>
      <?php echo CHtml::ajaxLink(
        Yii::t('delt', 'Link the firm «%name%» to this challenge', array('%name%'=>Yii::app()->controller->firm)),
        $url=CHtml::normalizeUrl(array('challenge/connect', 'id'=>$challenge->id, 'slug'=>Yii::app()->controller->firm->slug)),
        array(
          'replace' => '#challenge',
          'type' => 'POST',
          ),
        array(
          'title' => Yii::t('delt', 'Connect this firm with the active challenge'),
          )
        )
        ?>

    <?php else: ?>
      <?php echo Yii::t('delt', 'Begin with forking this firm:') ?> <?php echo CHtml::link($challenge->exercise->firm->parent, array('firm/fork', 'slug'=>$challenge->exercise->firm->parent->slug)) ?>
    <?php endif ?>
  <?php endif ?>
  </p>
  <hr />
  </div><!-- challenge_firm -->

  <div id="challenge_context">
    <h3><?php echo Yii::t('delt', 'Context') ?></h3>
  <?php echo CHtml::encode($challenge->exercise->description) ?>
  <?php echo $md->transform($challenge->exercise->introduction) ?>
  <hr />
  </div><!-- challenge_context -->

  <?php $this->render('_transactions', array('md'=>$md, 'challenge'=>$challenge, 'challenge_visibility'=>$challenge_visibility)) ?>
  
  </div><!-- challenge -->
<?php endif ?>
