  <div id="transactions">
    
  <h3><?php echo Yii::t('delt', 'Transactions') ?></h3>
  <?php foreach($challenge->exercise->transactions as $transaction): ?>
    <div class="transaction">
      <p><?php echo Yii::app()->dateFormatter->formatDateTime($transaction->event_date, 'short', null) ?><br />
      <?php if($transaction->id === $challenge->transaction_id): ?>
        <?php echo "CURRENT" ?>
      <?php endif ?>
      
      <?php echo CHtml::ajaxLink(
        $transaction->id === $challenge->transaction_id ? '' : Yii::t('delt', 'Mark current'),
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
      </p>

      <?php echo $md->transform($transaction->description) ?>
      
      
    </div>
  <?php endforeach ?>
  </div>
