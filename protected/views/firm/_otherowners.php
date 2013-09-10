<?php if(sizeof($other_owners)>0): ?>
<p><?php echo Yii::t('delt', 'This firm is currently shared with another user:|This firm is currently shared with other {n} users:', sizeof($other_owners)) ?></p>
<ul>
  <?php foreach($other_owners as $user): ?>
    <li>
      <?php echo $user->username ?>
      <?php if($user->first_name or $user->last_name): ?>
        (<?php echo $user->first_name . ' ' . $user->last_name ?>)
      <?php endif ?>
    </li>
  <?php endforeach ?>
</ul>
<?php else: ?>
<p><?php echo Yii::t('delt', 'This firm is not currently shared with any other user.') ?></p>
<?php endif ?>
