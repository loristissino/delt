<?php /* @var $this Controller */ 

$cs = Yii::app()->getClientScript(); 

$cs->registerScriptFile(
  Yii::app()->request->baseUrl.'/js/remark.min.js',
  CClientScript::POS_HEAD
);

?>

<!DOCTYPE html>
<html>
  <head>
  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/remark.css" >
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/html5.css" >
  <?php if($this->css): ?>
  <style><?php echo $this->css ?></style>
  <?php endif ?>
  <meta charset="utf-8" />
  </head>
  <body>
      <textarea id="source">
<?php echo $content; ?>
      </textarea>
    
    <script type="text/javascript">
       var slideshow = remark.create();
    </script>
  <?php // if(isset(Yii::app()->params['analytics'])) include_once(Yii::app()->params['analytics']) ?>
  <?php //include_once('_cookie_monster.php') ?>
  </body>
</html>
