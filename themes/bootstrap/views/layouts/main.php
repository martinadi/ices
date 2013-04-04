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
	            	array('label'=>'Schedule', 'url'=>'#'),
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
					href="http://twitter.com/ozibox" target="_blank">@ozibox</a>
			</p>
			<p>
				Code licensed under <a
					href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache
					License v2.0</a>, documentation under <a
					href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.
			</p>
			<p>
				<a href="http://glyphicons.com">Glyphicons Free</a> licensed under <a
					href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.
			</p>
			<ul class="footer-links">
				<li><a href="http://blog.getbootstrap.com">Blog</a></li>
				<li class="muted">·</li>
				<li><a href="https://github.com/twitter/bootstrap/issues?state=open">Issues</a>
				</li>
				<li class="muted">·</li>
				<li><a
					href="https://github.com/twitter/bootstrap/blob/master/CHANGELOG.md">Changelog</a>
				</li>
			</ul>
		</div>
	</footer>

</body>
</html>
