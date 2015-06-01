<?php $this->widget('ext.CookieMonster.CookieMonster', array('content'=>array(
  'mainMessage'=>UserModule::t('We use cookies to store the information concerning your activities on our website.') . ' ' . UserModule::t('See our <a href="{privacy_url}">privacy policy</a> to find out more.', array('{privacy_url}'=>Yii::app()->params['privacy_url'])), 
  'buttonMessage'=>UserModule::t('I understand')
  ))); ?>
