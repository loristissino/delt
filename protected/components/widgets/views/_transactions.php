  <div id="challenge_transactions" style="display: <?php echo $challenge_visibility=='journal' ? 'visible': 'none' ?>">
    
  <h3><?php echo Yii::t('delt', 'Transactions') ?></h3>
  <?php foreach($challenge->exercise->transactions as $transaction):
    $is_current = $transaction->id == $challenge->transaction_id;
    $ok = isset($result['transactions'][$transaction->id]) && $result['transactions'][$transaction->id]['points']>0?>
    <div class="transaction <?php echo $is_current ? 'current':'noncurrent' ?>" data-id=<?php echo $transaction->id ?>>
      
      <div class="firstline">
        <?php if(isset($result['transactions'][$transaction->id]) && $result['transactions'][$transaction->id]['checked']):?>
        <?php if($ok): ?>
          <?php echo Yii::app()->controller->createIcon('accept', Yii::t('delt', 'OK'), array('width'=>16, 'height'=>16)) ?>
        <?php else: ?>
          <?php echo Yii::app()->controller->createIcon('exclamation', Yii::t('delt', 'Errors'), array('width'=>16, 'height'=>16)) ?>
        <?php endif ?>
        <?php endif ?>
      <?php if($is_current): ?>
        <?php echo Yii::app()->controller->createIcon('page_edit', Yii::t('delt', 'Current transaction'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'The transaction you are working on'))); ?>
        <?php echo CHtml::ajaxLink(
          ' ',
          $url=CHtml::normalizeUrl(array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id)),
          array(
            'replace' => '#challenge',
            'type' => 'POST',
            ),
          array(
            'title' => Yii::t('delt', 'Mark this transaction as current'),
            'class' => 'noshownlink',
            )
          )
        ?>
        <?php echo Yii::app()->dateFormatter->formatDateTime($transaction->event_date, 'short', null) ?>
        
      <?php else: ?>
        
      <?php echo CHtml::ajaxLink(
        Yii::app()->controller->createIcon('page', Yii::t('delt', 'A transaction to prepare the journal entry for'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'A transaction to prepare the journal entry for')))
        . ' '
        . Yii::app()->dateFormatter->formatDateTime($transaction->event_date, 'short', null),
        $url=CHtml::normalizeUrl(array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id)),
        array(
          'update' => '#challenge',
          'type' => 'POST',
          ),
        array(
          'title' => Yii::t('delt', 'Mark this transaction as current'),
          'class' => 'noshownlink',
          )
        )
      ?>
      <?php endif ?>
       
      <?php if($transaction->hint): $has_hint = $challenge->hasHint($transaction->id) ?>
        <?php if (!($has_hint || !$is_current)): ?>
        - 
        <?php endif ?>
        <?php echo CHtml::ajaxLink(
          ($has_hint || !$is_current) ? '' : Yii::t('delt', 'Hint'),
          // we have to prepare ajax links even if we don't need them (because of ajax calls), so we provide empty ones
          $url=CHtml::normalizeUrl(array('challenge/requesthint', 'id'=>$challenge->id, 'transaction'=>$transaction->id)),
          array(
            'update' => '#challenge',
            'type' => 'POST',
            ),
          array(
            'title' => Yii::t('delt', 'Request a hint for this transaction') . ' (-' . Yii::t('delt', '1 point|{n} points', $transaction->penalties) . ')',
            'confirm' => Yii::t('delt', 'Do you want to receive a hint for this transaction?') . ' ' . Yii::t('delt', 'It will cost you one point.|It will cost you {n} points.', $transaction->penalties),
            )
          )
        ?>
      <?php endif ?>
      
      <?php $been_shown = $challenge->beenShown($transaction->id) ?>
      <?php if ($is_current && $challenge->isHelpAllowed()): ?>
      - 
      <?php endif ?>
      <?php echo CHtml::ajaxLink(
        ($is_current && $challenge->isHelpAllowed()) ? Yii::t('delt', 'Help') : '',
        // we have to prepare ajax links even if we don't need them (because of ajax calls), so we provide empty ones
        $url=CHtml::normalizeUrl(array('challenge/requesthelp', 'id'=>$challenge->id, 'transaction'=>$transaction->id)),
        array(
          'update' => '#journalentries_shown',
          'type' => 'POST',
          ),
        array(
          'title' => Yii::t('delt', 'Let me show you how this transaction should be recorded') . ($been_shown? '' : ' (-' . Yii::t('delt', '1 point|{n} points', $transaction->points) . ')'),
          'confirm' => $been_shown ? null : Yii::t('delt', 'Do you want to be shown how to record this transaction?') . ' ' . Yii::t('delt', 'It will cost you one point.|It will cost you {n} points.', $transaction->points),
          )
        )
      ?>
      
      - <?php echo Yii::t('delt', 'One point|{n} points', $transaction->points) ?>
      
      <span id="quicklinks<?php echo $transaction->id ?>" style="display:none">
        <?php echo CHtml::link(Yii::app()->controller->createIcon('application_form', Yii::t('delt', 'New journal entry'), array('width'=>16, 'height'=>16)), array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id, 'redirect'=>'bookkeeping/newjournalentry'), array('title'=>Yii::t('delt', 'New journal entry'))); ?>
        <?php echo CHtml::link(Yii::app()->controller->createIcon('template', Yii::t('delt', 'Use template'), array('width'=>16, 'height'=>16)), array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id, 'redirect'=>'template/admin'), array('title'=>Yii::t('delt', 'Use a template'))); ?>
        <?php echo CHtml::link(Yii::app()->controller->createIcon('closingentry', Yii::t('delt', 'Closing entry'), array('width'=>16, 'height'=>16)), array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id, 'redirect'=>'bookkeeping/closingjournalentry'), array('title'=>Yii::t('delt', 'Closing entry'))); ?>
        
        <?php if(Yii::app()->controller->firm): ?>
          <?php $entries = $transaction->getJournalEntriesFromFirm(Yii::app()->controller->firm->id); ?>
          <?php if (sizeof($entries)): ?>
            <small><?php echo Yii::t('delt', 'Linked journal entries') ?>:
            <span id="linkstoentries<?php echo $transaction->id ?>">
              <?php $count=0; foreach ($transaction->getJournalEntriesFromFirm(Yii::app()->controller->firm->id) as $je):?>
                <?php echo CHtml::link(++$count, array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id, 'entry'=>$je->id), array('title'=>Yii::t('delt', 'Edit journal entry'), 'class'=>'noshownlink')) ?>
              <?php endforeach ?>
            </span>
            </small>
          <?php else: ?>
            <?php if ($challenge->wasDeclaredNotEconomic($transaction->id)): ?>
              not economic
            <?php else: ?>
              <?php echo CHtml::ajaxLink(
                Yii::app()->controller->createIcon('not_economic', Yii::t('delt', 'No journal entry needed'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'No journal entry needed'))),
                $url=CHtml::normalizeUrl(array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id, 'declaration'=>'not_economic')),
                array(
                  'update' => '#challenge',
                  'type' => 'POST',
                  ),
                array(
                  'title' => Yii::t('delt', 'Declare that this transaction does not need a journal entry'),
                  'class' => 'noshownlink',
                  )
                )
              ?>
            <?php endif ?>
          <?php endif ?>
        <?php endif ?>
        
      </span>
      </div><!-- firstline -->
      
      <div class="description">
      <?php echo $md->transform($transaction->description) ?>
      </div>
      <?php if($is_current && $challenge->hasHint($transaction->id)): ?>
        <div class="hint">
        <p><?php echo Yii::app()->controller->createIcon('lightbulb', Yii::t('delt', 'Hint'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'A hint for the current transaction'))); ?></p>
        <?php echo $md->transform($transaction->hint) ?>
        </div>
      <?php endif ?>
      <?php if(isset($result['transactions'][$transaction->id])): ?>
        <div class="result">
          <?php echo Yii::app()->controller->renderPartial('/challenge/_checks', array('source'=>$result['transactions'][$transaction->id], 'with_oks'=>false)) ?>
        </div>
      <?php endif ?>
    </div>
  <?php endforeach ?>
  <br />
  
  <div id="journalentries_shown" class="journalentries_shown"></div>

  <hr />
  <!-- transaction --></div>

