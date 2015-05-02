<?php

$md = new CMarkdown();
$challenge = $this->getChallenge();

if($challenge)
{
  $params = CJavaScript::encode(array('transaction'=>$challenge->transaction_id));

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
}

?>

<?php if($challenge): ?>

  <div id="challenge" >

  <?php echo CHtml::script('params = ' . $params . '; execute(params);') 
  // we need to call execute() directly, to handle ajax updates reactions ?>
  <h2><?php echo Yii::app()->controller->createIcon('book_open', Yii::t('delt', 'Open Challenge'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'The running challenge'))); ?> <?php echo CHtml::encode($challenge->exercise->title) ?></h2>

  <div id="challenge_context">
  <p><?php echo CHtml::encode($challenge->exercise->description) ?></p>
  <?php echo $md->transform($challenge->exercise->introduction) ?>
  <p>
  <?php if($challenge->firm_id): ?>
    <?php echo Yii::t('delt', 'Associated firm:') ?> <?php echo $challenge->firm ?>
    <?php if(isset(Yii::app()->controller->firm) && $challenge->firm_id != Yii::app()->controller->firm->id): ?>
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
  </div><!-- challenge_context -->

  <?php $this->render('_transactions', array('md'=>$md, 'challenge'=>$challenge)) ?>
  
  </div>
<?php endif ?>
