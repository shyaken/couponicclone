<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2><?php echo Yii::t('uniprogy','Error {code}',array('{code}' => $code)); ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>