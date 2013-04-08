<h2>
	Add Music
	<small>for <?php echo $playlist->name?></small>
</h2>

<script>
function reloadGrid(data) {
    $.fn.yiiGridView.update('music-grid');
}
</script>

<?php 
$form=$this->beginWidget('CActiveForm', array(
		'enableAjaxValidation'=>true,
));
 
	$this->widget('bootstrap.widgets.TbGridView',array(
			'id'=>'music-grid',
			'dataProvider'=>$music->findForPlaylist1(),
			'filter'=>$music,
			'columns'=>array(
					array(
						'class' => 'CCheckBoxColumn',
						'id' => 'music_id',
						'selectableRows' => '50',
					),
					'title','artist','album','playtime_string',				
			),
	));

	echo CHtml::ajaxSubmitButton('Add',
			array('ajaxAddMusic', 'id' => $playlist->playlist_id), 
			array('success'=>'reloadGrid'),
			array('class' => 'btn btn-primary'));

$this->endWidget();
?>