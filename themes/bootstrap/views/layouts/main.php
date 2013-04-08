<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

	<?php $this->widget('bootstrap.widgets.TbNavbar',array(
	    'items'=>array(
	        array(
	            'class'=>'bootstrap.widgets.TbMenu',
	            'items'=>array(
	                array('label'=>'Now Playing', 'url'=>array('/nowplaying/index')),
            		array('label'=>'Library', 'url'=>'#', 'items' => array(
            				array('label'=>'Music', 'url'=>array('/music/index')),
            				array('label'=>'Playlist', 'url'=>array('/playlist/index')),
            		)),
	            	array('label'=>'Schedule', 'url'=>array('/schedule/index')),
					array('label'=>'Statistics', 'url'=>'#', 'items' => array(
						array('label' => 'Current Listener', 'url' => array('/statistic/current')),
						array('label' => 'Listener Report', 'url' => array('/statistic/report')),
					)),
	            	array('label'=>'Settings', 'url'=>'#'),
					array('label'=>'About', 'url'=>'#'),
	            ),
	        ),
	    ),
	)); ?>
	<div id="wrap">
		<div class="container" id="page">
		
			<?php if(isset($this->breadcrumbs)):?>
				<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
				)); ?><!-- breadcrumbs -->
			<?php endif?>
		
			<?php echo $content; ?>
		
		</div><!-- page -->
		<div id="push"></div>
	</div>

	<div class="clear"></div>

	<footer class="footer" id="footer">
		<div class="container">
			<p>
				Designed and built with all the love in the world by <a
					href="http://twitter.com/martinadiyono" target="_blank">@martinadiyono</a>
			</p>			
		</div>
	</footer>

</body>
</html>
