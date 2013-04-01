<?php

  $languages = Yii::app()->params['available_languages'];
  list($section, $language, $page)=explode('/', $this->action->view);
  unset($languages[$language]);

  $links=array();
  
  foreach($languages as $id => $name)
  {
    $links[]=CHtml::link($name, $this->createUrl('site/page', array('view'=>$id. '.'. $page)));
  }

?>
<hr />
<div id="languages">
<p><?php echo Yii::t('delt', 'This page in other languages:') ?>&nbsp;<?php echo implode(' - ', $links) ?></p>
</div>
