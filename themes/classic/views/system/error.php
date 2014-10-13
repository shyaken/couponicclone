<?php
$this->pageTitle=Yii::app()->name . ' - Error';
app()->controller->beginContent('/layouts/roundedBox'); ?>
<h2><?php echo Yii::t('uniprogy','Error {code}',array('{code}' => $code)); ?></h2>
<div class="error">
<?php echo CHtml::encode($message); ?>
</div>
<?php
app()->controller->endContent();