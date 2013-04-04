<h2>
	Playlist
	<small><?php echo CHtml::link('Create list', array('create'))?></small>
</h2>
<?php
$this->widget('bootstrap.widgets.TbGridView',array(
		'id'=>'playlist-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'columns'=>array(
				'name','created','updated',
				array(
						'class'=>'bootstrap.widgets.TbButtonColumn',
				),
		),
));