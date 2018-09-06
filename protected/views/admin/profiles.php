<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Users</h1>

<textarea cols="80">
<?php

    foreach($users as $user)
    {
      if ($user->status>0)
      {
        $profile=Profile::model()->findByPK($user->id);
        $name = $profile->getFullName();
        if ($name=='[Incognito user]')
        {
          $name = $user->username;
        }
        $dear = $profile->first_name;
        if (!$dear)
        {
          $dear = $user->username;
        }
        echo implode("\t", array($user->id, $user->username, $name, $profile->language, $dear, $user->email)) . "\n";
      }
    }

?>

</textarea>
