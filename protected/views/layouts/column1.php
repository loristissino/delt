<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
  <?php $this->renderPartial('/layouts/_flashes'); ?>
  <?php echo $content; ?>
<?php $this->endContent(); ?>
</div><!-- content -->
