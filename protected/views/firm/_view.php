<?php
/* @var $this FirmController */
/* @var $data Firm */
?>

<div class="view">

	<?php echo CHtml::link(CHtml::encode($data->name), array('firms/'.$data->slug)) ?>
	<br />

</div>
