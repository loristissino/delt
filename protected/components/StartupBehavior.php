<?php
class StartupBehavior extends CBehavior
{
    public function attach($owner){
        $owner->attachEventHandler('onBeginRequest', array($this, 'beginRequest'));
    }

    public function beginRequest(CEvent $event){
        $language=Yii::app()->request->getPreferredLanguage();
        $info=explode('_', $language);
        if(sizeof($info)>1)
        {
          list($lang, $country)=explode('_', $language);
        }
        else
        {
          $lang=$language;
        }
        Yii::app()->language=$lang;
    }
}
