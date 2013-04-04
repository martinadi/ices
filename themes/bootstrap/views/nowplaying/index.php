<div class="alert in alert-block fade alert-success">
	<a class="close" data-dismiss="alert">×</a><strong>Now Playing</strong> >>
	<?php echo $nowPlaying[0]?> - <?php echo $nowPlaying[1]?>
</div>
<?php

$form=$this->beginWidget('CActiveForm', array(
		'enableAjaxValidation'=>true,
));
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