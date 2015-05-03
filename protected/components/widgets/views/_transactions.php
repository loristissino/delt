  <div id="challenge_transactions" style="display: <?php echo $challenge_visibility=='journal' ? 'visible': 'none' ?>">
    
  <h3><?php echo Yii::t('delt', 'Transactions') ?></h3>
  <?php foreach($challenge->exercise->transactions as $transaction): $is_current = $transaction->id == $challenge->transaction_id ?>
    <div class="transaction <?php echo $is_current ? 'current':'noncurrent' ?>" data-id=<?php echo $transaction->id ?>>
      
      <div class="firstline">
      <?php if($is_current): ?>
        <?php echo Yii::app()->controller->createIcon('page_edit', Yii::t('delt', 'Current transaction'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'The transaction you are working on'))); ?>
        <?php echo CHtml::ajaxLink(
          ' ',
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
       
      <?php if($transaction->hint): ?>
        <?php if(!($has_hint = $challenge->hasHint($transaction->id))): ?>
        - 
        <?php endif ?>
        <?php echo CHtml::ajaxLink(
          $has_hint ? '' : Yii::t('delt', 'Request hint'),
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
      - <?php echo Yii::t('delt', 'One point|{n} points', $transaction->points) ?>
      
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
    </div>
  <?php endforeach ?>
  </div>
