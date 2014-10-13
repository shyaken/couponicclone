<?php
$mailer->Subject = $deal->name;

echo $deal->name ."\n". aUrl('/deal/view',array('url' => $deal->url),'http') ."\n\n";
echo $this->t('Company Information').':'."\n";
echo $deal->company->name."\n";
echo $deal->company->website."\n";
echo wm()->get('location.helper')->locationAsText(
	$deal->company->loc,
	$deal->company->address,
	$deal->company->zipCode,
	"\n"
)."\n\n";

echo strip_tags($deal->description);
echo "\n\n";

echo $this->t('More Great Deals')."\n\n";
foreach($side as $sdeal)
	echo CHtml::link($sdeal->name,aUrl('/deal/view',array('url' => $sdeal->url),'http'))."\n";