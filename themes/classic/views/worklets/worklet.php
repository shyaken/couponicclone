<div id="<?php echo $id; ?>" class="worklet">
<?php
$roundedBox = ($this->space == 'content' || $this->space == 'sidebar') && strpos($this->getDOMId(),'Tabs')===false;
if($roundedBox) app()->controller->beginContent('/layouts/roundedBox');
$this->render('workletStd', array('title' => $title, 'id' => $id, 'content' => $content));
if($roundedBox) app()->controller->endContent();
?>
</div>