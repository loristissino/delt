<?php
/* @var $this ChallengeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
  'Challenges',
);

?>

<h1><?php echo Yii::t('delt', 'Challenges') ?></h1>

<div>
<?php echo Yii::t('delt', 
    'Sorry, you cannot accept challenges if your <a href="{url}">profile</a> is not complete.',
    array('{url}'=>$this->createUrl('/user/profile/edit'))
    ); ?> 
</div>