<?php
$this->breadcrumbs=array(
	UserModule::t('Users')=>array('/user'),
	UserModule::t('Manage')=>array('/user/admin'),
    'Not Activated',
);

$this->menu=array(
    array('label'=>UserModule::t('Create User'), 'url'=>array('create')),
    array('label'=>UserModule::t('Manage Users'), 'url'=>array('admin')),
    array('label'=>UserModule::t('Manage Profile Field'), 'url'=>array('profileField/admin')),
    array('label'=>UserModule::t('List User'), 'url'=>array('/user')),
);

?>
<h1><?php echo UserModule::t("Users that have not been activated within 24 hours"); ?></h1>

<?php if(sizeof($users)): ?>
    <ul>
    <?php foreach($users as $user): ?>
        <li><?php echo $user->email . ', <b>'. $user->username . '</b> (' . $user->create_at . ')' ?></li>
    <?php endforeach ?>
    </ul>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'DeleteNotActivatedUsersForm',
        'enableAjaxValidation'=>false,
        'method'=>'POST'
    )); ?>

    <div class="row">
      <?php echo CHtml::submitButton(Yii::t('Users', 'Delete'), array('name'=>'Delete')) ?>
    </div>

  <?php $this->endWidget() ?>


<?php else: ?>
<p>No users found.</p>
<?php endif ?>
