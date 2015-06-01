<?= CHtml::openTag('div', $outerHtmlOptions) ?>
<?= CHtml::openTag('div', $innerHtmlOptions) ?>
<?= Yii::t($content['category'], $content['mainMessage'], $content['mainParams'], $content['source'], $content['language']) ?>
<?= CHtml::htmlButton(Yii::t($content['category'], $content['buttonMessage'], $content['buttonParams'], $content['source'], $content['language']), $buttonHtmlOptions) ?>
<?= CHtml::closeTag('div'); ?>
<?= CHtml::closeTag('div') . "\n"; ?>