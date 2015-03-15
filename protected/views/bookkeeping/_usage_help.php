<div id="usage" style="display: <?php echo $display?>">
<h2><?php echo Yii::t('delt', $title) ?></h2>
<div class="<?php echo $class ?>">
<?php 
$hbp = new HandbookManager(Yii::app()->language, $section, Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . 'handbook');
echo $hbp->getRenderedContent();
?>
</div>
</div>
