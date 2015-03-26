<table style="width: 700px;" class="<?php echo $class ?>">
<tr>
  <th style="width: 200px;"><?php echo Yii::t('delt', 'Account') ?></th>
  <th style="width: 200px;"><?php echo Yii::t('delt', 'Classification') ?></th>
  <th style="width: 200px;"><?php echo Yii::t('delt', 'Change') ?></th>
  <th style="width: 100px;"><?php echo Yii::t('delt', 'Value') ?></th>
</tr>
<?php foreach($items as $item): $analysis=$item->analysis ?>
  <?php if(isset($analysis['account'])): ?>
    <tr>
      <td>
        <?php echo $analysis['account']?>
      </td>
      <td>
        <?php echo $analysis['classification'] ?>
        <?php if($analysis['type']=='C'): ?>
          (<?php echo Yii::t('delt', 'Contra Account') ?>)
        <?php endif ?>
      </td>
      <td>
        <?php echo $this->renderPartial('../bookkeeping/_change', array('change'=>$analysis['change'], 'type'=>$analysis['type'])) ?>
      </td>
      <td class="currency"><?php echo $analysis['value'] ?></td>
    </tr>
  <?php endif ?>
<?php endforeach ?>
</table>


