<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1><?php echo Yii::t('delt', 'Welcome to <i>{name}</i>i>', array('{name}'=> Yii::app()->name)) ?></h1>

<p>The application is still under development. Some rules applying now:</p>
<ul>
  <li>everything you write can be deleted</li>
  <li>everything you write can be shared</li>
  <li>use it at your own risk</li>
</ul>

<p>A full legal statement will be provided in the future.</p>

<hr />

<p>Themes: <?php foreach(array('classic', 'mobile') as $theme): ?>
<?php if(Yii::app()->session['theme']!=$theme): ?>
<?php echo CHtml::link(ucfirst($theme), $this->createUrl('browser/index', array('theme'=>$theme))) ?>&nbsp;
<?php endif ?>
<?php endforeach ?>
</p>
