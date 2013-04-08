<?php
class ScheduleController extends Controller{
	public function actionIndex(){
		$playlist = Playlist::model()->findAll(array('order' => 'name'));
		$this->render('index', array('playlists' => $playlist));
	}
	
	public function actionGetEndEvent(){
		
		$date_start = strtotime($_POST['date_start']);
		 
		$date_end = $date_start + $_POST['duration'];
		//Fri Apr 05 2013 07:30:00 GMT+0700 (WIT)
		$result = array('date_end' => date('D M d Y H:i:s eO (T)',$date_end), 
				'input' => array(
						$date_start, 
						$_POST['duration'],
						date('D M d Y H:i:s',$date_start)));
		
		echo CJSON::encode($result);
		
	}
	
	public function actionSaveEvent(){
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
			$columns = array(
				'playlist_id' => $_POST['playlistId'],
				'start' => strtotime($_POST['start']),
				'end' => strtotime($_POST['end']),				
			);
			$rs = Yii::app()->db->createCommand()->insert('schedule', $columns);
			echo CJSON::encode(array('status' => $rs));
		}
	}
	
	public function actionUpdateEvent(){
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){			
			$columns = array(					
					'start' => strtotime($_POST['start']),
					'end' => strtotime($_POST['end']),
			);
			$rs = Yii::app()->db->createCommand()->update('schedule', $columns, 'id=:id', array(':id' => $_POST['id']));
			echo CJSON::encode(array('status' => $rs));
		}
	}
	
	public function actionRemoveEvent(){
		if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){				
			$rs = Yii::app()->db->createCommand()->delete('schedule', 'id=:id', array(':id' => $_POST['id']));
			echo CJSON::encode(array('status' => $rs));
		}
	}
	
	public function actionAjaxFeed(){
		$schedule = YII::app()->db->createCommand()
						->select('a.*, b.name as title')
						->from('schedule a')
						->join('playlist b', 'a.playlist_id = b.playlist_id')
						->where('a.start between :start and :end', array(':start' => $_GET['start'], 'end' => $_GET['end']))
						->queryAll();
		
		echo CJSON::encode($schedule);
	}
}