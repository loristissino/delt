<?php

$md = new CMarkdown();

if($challenge)
{
  $params = CJavaScript::encode(array(
    'transaction'=>$challenge->transaction_id,
    'i18n'=>array(
      'toggle_firm'=>Yii::t('delt', 'Firm'),
      'toggle_context'=>Yii::t('delt', 'Introduction'),
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

  $cs->registerScriptFile(
    Yii::app()->request->baseUrl.'/js/jquery.pin.js',
    CClientScript::POS_HEAD
  );

$challenge_visibility = Yii::app()->controller->challenge_visibility;

$challenge_firm_set = $challenge->hasFirm();
// there is a firm set for the challenge

$challenge_firm_current = isset(Yii::app()->controller->firm) && $challenge->firm_id == Yii::app()->controller->firm->id;
// the current firm is the same of the challenge

$is_ajax = Yii::app()->controller->is_ajax;

}

?>


<?php if($challenge && $challenge_visibility!='none'): ?>

  <div id="challenge" lang="<?php echo $challenge->exercise->firm->language->language_code ?>">

  <?php echo CHtml::script('params = ' . $params . '; execute(params);') 
  // we need to call execute() directly, to handle ajax updates reactions ?>
  <h2>
    <?php echo Yii::app()->controller->createIcon('book_open', Yii::t('delt', 'Open Challenge'), array('id'=>'challenge_icon', 'width'=>16, 'height'=>16, 'style'=>'cursor: pointer', 'title'=>Yii::t('delt', 'The running challenge'))); ?>
    <span id="challenge_commands" class="challengeinfo"></span>
    <?php echo CHtml::encode($challenge->exercise->title) ?>
    <?php if($challenge->method & Challenge::SHOW_POINTS_DURING_CHALLENGE): ?>
    <span class="challengeinfo"> - 
      <span class="score">
        <?php echo Yii::t('delt', 'Current score: {percentage}%', array(
          '{percentage}'=>Yii::app()->numberFormatter->formatDecimal(
            $challenge->rate/10)))
        ?>
      </span>
    <?php endif ?>
    </span>
  </h2>

  <div id="challenge_firm" style="display: <?php echo !($challenge_firm_set && $challenge_firm_current)&&(!$is_ajax) ? 'visible': 'none' ?>">
    <h3><?php echo Yii::t('delt', 'Firm') ?></h3>
  <p>
  <?php if($challenge_firm_set): ?>
    <?php echo Yii::t('delt', 'Firm linked to the current challenge:') ?> <?php echo CHtml::link($challenge->firm, array('bookkeeping/manage', 'slug'=>$challenge->firm->slug), array('title'=>Yii::t('delt', 'Management'))) ?>.
    
    <?php if($challenge->firm->firm_parent_id != $challenge->exercise->firm->firm_parent_id): ?>
      <?php echo Yii::app()->controller->createIcon('bell', Yii::t('delt', 'Warning'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'You are working with a firm that does not have the same parent of the benchmark.'))) ?>
    <?php endif ?>
    
    <br />
    <?php if(!$challenge_firm_current && Yii::app()->controller->firm): ?>
      <?php echo Yii::app()->controller->createIcon('bell', Yii::t('delt', 'Warning'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'We have a problem here.'))) ?>
      <?php echo Yii::t('delt', 'You are not currently working with the firm linked to this challenge, but with «{name}».', array('{name}'=>Yii::app()->controller->firm)) ?>
      <?php echo CHtml::link(
        Yii::t('delt', 'Link current firm'),
        $url=CHtml::normalizeUrl(array('challenge/connect', 'id'=>$challenge->id, 'slug'=>Yii::app()->controller->firm->slug)),
        array(
          'submit'=>$url,
          'title' => Yii::t('delt', 'Link the firm «{name}» to this challenge', array('{name}'=>Yii::app()->controller->firm)),
          )
        )
        ?>.
    <?php else: ?>
      <?php echo Yii::t('delt', 'If you start working with another firm, you will have the possibility to link it to the current challenge.') ?>
    <?php endif ?>
    
  <?php else: ?>
    <?php if(Yii::app()->controller->firm): ?>
      <?php echo CHtml::link(
        Yii::t('delt', 'Link the firm «{name}» to this challenge', array('{name}'=>Yii::app()->controller->firm)),
        $url=CHtml::normalizeUrl(array('challenge/connect', 'id'=>$challenge->id, 'slug'=>Yii::app()->controller->firm->slug)),
        array(
          'submit'=>$url,
          'title' => Yii::t('delt', 'Connect this firm with the active challenge'),
          )
        )
        ?>.

    <?php else: ?>
      <?php if($challenge->exercise->firm->parent): ?>
      <?php echo Yii::t('delt', 'Begin with forking this firm:') ?> <?php echo CHtml::link(CHtml::encode($challenge->exercise->firm->parent), array('firm/fork', 'slug'=>$challenge->exercise->firm->parent->slug)) ?>.<br />
      <?php echo Yii::t('delt', 'If you prefer, you can instead start working with another firm you own and link it to this challenge later (just make sure that the firm\'s parent is the one you see here above).') ?>
      <?php endif ?>
    <?php endif ?>
  <?php endif ?>
  
  <?php if($challenge_firm_set): ?>
  <?php /* TODO
  <br />
  <?php echo "unlink" */ ?>
  <?php endif ?>
  </p>
  <hr />
  </div><!-- challenge_firm -->

  <div id="challenge_context">
    <h3><?php echo Yii::t('delt', 'Introduction') ?></h3>
  <p class="description"><?php echo CHtml::encode($challenge->exercise->description) ?></p>
  <div class="introduction"><?php echo $md->transform($challenge->exercise->introduction) ?></div>
  <hr />
  </div><!-- challenge_context -->

  <?php $this->render('_transactions', array('md'=>$md, 'challenge'=>$challenge, 'challenge_visibility'=>$challenge_visibility, 'result'=>$this->result)) ?>
  
  <div id="exercise_copyrightnotice">
    <?php echo Yii::t('delt', 'Exercise prepared by {author}.', array('{author}'=>$challenge->exercise->getOwnerProfile()->getFullName())) ?>
    <?php echo Yii::t('delt', 'Some rights reserved.') ?><br />
    <?php echo Yii::t('delt', 'Released under a <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)) ?>
  
  </div>
  <hr />
  
  <!-- challenge --></div>
<?php endif ?>
