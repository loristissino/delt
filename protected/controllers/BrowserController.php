<?php

class BrowserController extends Controller
{
    public function actionIndex($theme='classic')
    {
      Yii::app()->session['theme'] = $theme;
      $referrer = Yii::app()->request->urlReferrer ? Yii::app()->request->urlReferrer : $this->createUrl('/'); 
      Yii::app()->request->redirect($referrer);
    }
}
