<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<pre>
<?php

$firm = Firm::model()->FindByPK(1);

echo $firm->name . "\n";

foreach($firm->accounts() as $account)
{
  echo $account->code . "\n";
}

$user = DEUser::model()->findByPK(2);

echo $user->username . "\n";

foreach($user->firms as $firm)
{
  echo $firm->name . "\n";
}


?>
</pre>
