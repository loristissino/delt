<p>
  <?php echo Yii::t('delt', 'You reached the number of firms maneageble with your account ({number}).', array('{number}'=>$this->DEUser->profile->allowed_firms)) ?>
  <?php echo Yii::t('delt', 'If you want to create a new one, please delete some of the existing.') ?><br />
  <?php echo Yii::t('delt', '(If you really need it, you can ask us to be allowed to manage other firms.)') ?>
</p>

