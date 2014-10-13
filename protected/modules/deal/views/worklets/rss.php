<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
	<channel>
    	<title><?php echo app()->name; ?> » <?php
    		$location = wm()->get('location.helper')->locationToData($this->location,true);
    		echo $location->cityName;
    	?></title>
    	<description><?php echo $this->t($this->param('rssChannelDescription'),array(
    		'{city}' => $location->cityName
    	)); ?></description>
    	<link><?php echo htmlspecialchars(aUrl('/',wm()->get('location.helper')->urlParams($location))); ?></link>
    	<?php foreach($this->deals as $deal) { ?>
    	<item>
    		<title><?php echo htmlspecialchars($deal->name); ?></title>
    		<description>
    			<?php
    			if($deal->imageBin)
					echo htmlspecialchars(CHtml::image(app()->storage->bin($deal->imageBin)->getFileUrl('original'),
						$deal->name,array('align'=>'right','width'=>'120')));
				echo htmlspecialchars($deal->description);
				?>
    		</description>
    		<link><?php echo htmlspecialchars(aUrl('/deal/view',array('url'=>$deal->url))); ?></link>
    		<pubDate><?php echo date('r',$deal->start); ?></pubDate>
    	</item>
    	<?php } ?>
	</channel>
</rss>
