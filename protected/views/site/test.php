<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Test page</h1>

<pre>
<?php

$firm = Firm::model()->FindByPK(1);

echo $firm . "\n";

echo "---------------\n";

foreach($firm->accounts as $account)
{
  echo str_repeat('&nbsp;', 2* $account->level) . $account->code . ') ' . $account->name . ' (' . $account->outstanding_balance . ')'. "\n";
}

echo "---------------\n";
/*
foreach($firm->namedAccounts as $account)
{
  echo $account->code . ') ' . $account->name . ' ' . $account->outstanding_balance . "\n";
}
*/
/*
$user = DEUser::model()->findByPK(2);

echo $user->username . "\n";

foreach($user->firms as $firm)
{
  echo $firm->name . "\n";
}
*/



?>
</pre>
