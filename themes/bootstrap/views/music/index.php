<h2>
	Music
	<small><?php echo CHtml::link('Upload', array('upload'))?></small>
</h2>
<?php
$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'music-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title','artist','album', 'year', 'genre','playtime_string',
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
));