

<div class="row">
<div class="span4 offset4">
	<audio controls src="<?php echo $this->createAbsoluteUrl('preview', array('id' => $model->music_id))?>" style="width: 100%"></audio>
	<?php $this->widget('bootstrap.widgets.TbDetailView',array(
		'data'=>$model,
		'attributes'=>array(
			'title','artist','album', 'year','genre','playtime_string',
			array('type' => 'raw', 'label' => 'Bitrate', 'value' => floor($model->bitrate / 1000) . " kbps")		
		),
	)); 
	?>
</div>
</div>
