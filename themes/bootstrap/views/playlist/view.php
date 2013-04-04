<?php 
Yii::app()->clientScript->registerScript("init", "
$.fn.equalHeights = function(px) {
	$(this).each(function(){
		var currentTallest = 0;
		$(this).children().each(function(i){
			if ($(this).height() > currentTallest) { currentTallest = $(this).height(); }
		});
    if (!px && Number.prototype.pxToEm) currentTallest = currentTallest.pxToEm(); //use ems unless px is specified
		// for ie6, set height since min-height isn't supported
		if ($.browser.msie && $.browser.version == 6.0) { $(this).children().css({'height': currentTallest}); }
		$(this).children().css({'min-height': currentTallest}); 
	});
	return this;
};

// just in case you need it...
$.fn.equalWidths = function(px) {
	$(this).each(function(){
		var currentWidest = 0;
		$(this).children().each(function(i){
				if($(this).width() > currentWidest) { currentWidest = $(this).width(); }
		});
		if(!px && Number.prototype.pxToEm) currentWidest = currentWidest.pxToEm(); //use ems unless px is specified
		// for ie6, set width since min-width isn't supported
		if ($.browser.msie && $.browser.version == 6.0) { $(this).children().css({'width': currentWidest}); }
		$(this).children().css({'min-width': currentWidest}); 
	});
	return this;
};
", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript("ready", "
	$('#main-content').equalHeights();
", CClientScript::POS_READY);
?>

<div class="row-fluid">
	<div class="span6">
		<h2>
			<?php echo ucfirst($model->name)?>
		</h2>
	</div>
	<div class="span6">
		<?php echo CHtml::link('Play Now', array('playnow', 'id' => $model->playlist_id), array('class' => 'btn btn-large btn-success', 'style' => 'float:right'))?>
	</div>
</div>


<script>
function reloadGrid(data) {
    $.fn.yiiGridView.update('music1-grid');
    $.fn.yiiGridView.update('music2-grid');
}
</script>

<div class="row-fluid">
	<div class="span6" style="text-align: center;border-right: thin solid #dddddd;border-top: thin solid #dddddd;"><h3>Available</h3></div>
	<div class="span6" style="text-align: center;border-left: thin solid #dddddd;border-top: thin solid #dddddd;"><h3>Selected</h3></div>
</div>

<div class="row-fluid" id="main-content">
	<div class="span6" style="border-right: thin solid #dddddd; padding-right: 10px;">
		<?php 
		$form=$this->beginWidget('CActiveForm', array(
				'enableAjaxValidation'=>true,
		));
			echo CHtml::ajaxSubmitButton('>>',
					array('ajaxAddMusic', 'id' => $model->playlist_id),
					array('success'=>'reloadGrid'),
					array('class' => 'btn btn-primary', 'style' => 'float:right'));
			//echo "<h3>Music</h3>";
			echo "<div class='clear'></div>"; 

			$this->widget('bootstrap.widgets.TbGridView',array(
					'id'=>'music1-grid',
					'dataProvider'=>$musicNotIn->findNotIn(),
					'filter'=>$musicNotIn,
					'columns'=>array(
							array(
								'class' => 'CCheckBoxColumn',
								'id' => 'music_id',
								'selectableRows' => '50',
							),
							'title','artist','album','playtime',				
					),
			));
		
			
		
		$this->endWidget();
		?>
	</div>
	<div class="span6" style="border-left: thin solid #dddddd; padding-left: 10px;">
		<?php 
		$form=$this->beginWidget('CActiveForm', array(
				'enableAjaxValidation'=>true,
		));
		 
			echo CHtml::ajaxSubmitButton('<<',
					array('ajaxRemoveMusic'),
					array('success'=>'reloadGrid'),
					array('class' => 'btn btn-primary'));

			$this->widget('bootstrap.widgets.TbGridView',array(
					'id'=>'music2-grid',
					'dataProvider'=>$musicIn->search(),
					'filter'=>$musicIn,
					'columns'=>array(
							array(
								'class' => 'CCheckBoxColumn',
								'id' => 'playlist_music_id',
								'selectableRows' => '50',
							),'queue',
							array(
								'name' => 'music_title',
								'value' => '$data->Music->title',
								
							),
							array(
									'name' => 'music_artist',
									'value' => '$data->Music->artist',
							
							),
							array(
									'name' => 'music_album',
									'value' => '$data->Music->album',
							
							),
							array(
									'name' => 'music_playtime',
									'value' => '$data->Music->playtime',
							
							),
							//'Music.title','Music.artist', 'Music.album', 'Music.playtime'
							/* array(
								'class' => 'CCheckBoxColumn',
								'id' => 'music_id',
								'selectableRows' => '50',
							),
							'Music.title','Music.artist','Music.album','Music.playtime', */				
					),
			));
		
			
		
		$this->endWidget();
		?>
	</div>
</div>

