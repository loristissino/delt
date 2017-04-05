<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Chart of Accounts',
);

$accounts = $model->getAccountsAsDataProvider()->data;

?>
<h1><?php echo Yii::t('delt', 'Chart of Accounts') ?></h1>

<?php if(sizeof($accounts)): ?>
<?php foreach($accounts as $account): ?>
  <p style="padding-left: <?php echo 20*$account->level ?>px">
    <span class="code"><?php echo $account->code ?></span><br />
    <?php foreach($account->getNamesAsArray() as $locale=>$name): ?>
      <span class="locale"><?php echo $locale ?></span>&nbsp;<span class="name"><?php echo $name ?></span><br />
    <?php endforeach ?>
  </p>
<?php endforeach ?>
<?php endif ?>


<?php if(sizeof($accounts)): ?>
<table>
  <tr>
    <th>Code</th><th>English name</th><th>Italian name</th>
  </tr>
<?php foreach($accounts as $account): ?>
  <tr>
  <td style="padding-left: <?php echo 20*$account->level ?>px">
    <span class="code"><?php echo $account->code ?></span></td>
    <?php foreach($account->getNamesAsArray() as $locale=>$name): ?>
      <td><?php echo $name ?></td>
    <?php endforeach ?>
  </tr>
<?php endforeach ?>
</table>
<?php endif ?>

