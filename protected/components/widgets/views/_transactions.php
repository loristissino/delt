  <div id="transactions">
    
  <h3><?php echo Yii::t('delt', 'Transactions') ?></h3>
  <?php foreach($challenge->exercise->transactions as $transaction): $is_current = $transaction->id == $challenge->transaction_id ?>
    <div class="transaction <?php echo $is_current ? 'current':'noncurrent' ?>" data-id=<?php echo $transaction->id ?>>
      
      <div class="firstline">
      <?php if($is_current): ?>
        <?php echo Yii::app()->controller->createIcon('page_edit', Yii::t('delt', 'Current transaction'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'The transaction you are working on'))); ?>
        <?php echo CHtml::ajaxLink(
          '',
          $url=CHtml::normalizeUrl(array('challenge/activatetransaction', 'id'=>$challenge->id, 'transaction'=>$transaction->id)),
          array(
            'update' => '#challenge',
            'type' => 'POST',
            ),
          array(
            'title' => Yii::t('delt', 'Mark this transaction as current'),
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
      </div>
      
      <div class="description">
      <?php echo $md->transform($transaction->description) ?>
      </div>
    </div>
  <?php endforeach ?>
  </div>
