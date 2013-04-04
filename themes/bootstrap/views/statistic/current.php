<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'area-grid',
    'type'=>'striped bordered condensed',
    'dataProvider'=>$gridDataProvider,
    
    'columns'=>array(
    	array('name'=>'ip', 'header'=>'IP'),
    		
    		array('name'=>'countryName', 'header'=>'Country'),
    		array('name'=>'city', 'header'=>'City'),
    		array('name'=>'conected_time', 'header'=>'Conected Time'),
    		array('name'=>'user_agent', 'header'=>'User Agent'),
    ),
)); ?>