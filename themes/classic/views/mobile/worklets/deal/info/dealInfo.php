<h1><?php echo $this->deal->name; ?></h1>
<div class='box'><?php echo $this->deal->description; ?></div>
<?php if(count($this->deal->reviews)): ?>
<h3><?php echo $this->t('Reviews'); ?></h3>
<div class='box'>
<?php foreach($this->deal->reviews as $review) { ?>
<blockquote><?php echo app()->format->ntext($review->review); ?>
<span class='signature'>&ndash; <?php echo CHtml::link($review->name,$review->website,array('target'=>'_blank')); ?></span>
</blockquote>
<?php } endif; ?>
</div>