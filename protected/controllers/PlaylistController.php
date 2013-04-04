<?php
class PlaylistController extends Controller{
	public function actionCreate(){
		$this->breadcrumbs=array(
				'Playlist' => array('index'),
				'Create'
		);
		$model = new Playlist;
		
		if(isset($_POST['ajax']) && $_POST['ajax'] ==='Playlist-form'){
			echo CActiveForm::validate($formModel);
			Yii::app()->end();
		}
		
		if(isset($_POST['Playlist'])){
			$model->attributes=$_POST['Playlist'];
			// validate user input and redirect to the previous page if valid
			if($model->validate()){
				$model->attributes=$_POST['Playlist'];
				if($model->save()){
					$id = Yii::app()->db->getLastInsertID();
					$this->redirect(array('view', 'id' => $id));
				}
			}
		}
		
		$this->render('create', array('model' => $model));
	}
	
	public function actionView($id){
		$model = Playlist::model()->findByPk($id);
		
		$this->breadcrumbs=array(
				'Playlist' => array('index'),
				$model->name
		);
		
		$PlaylistHasMusic = PlaylistHasMusic::model()->findAllByAttributes(array('playlist_id' => $id));
		
		$in_array = array();
		
		foreach ($PlaylistHasMusic as $value){
			$in_array[] = $value->music_id;
		}
		
		$musicNotIn=new Music('findNotIn');
		$musicNotIn->unsetAttributes();  // clear any default values
		$musicNotIn->in_array = $in_array;
		if(isset($_GET['Music']))
			$musicNotIn->attributes=$_GET['Music'];
		
		$musicIn=new PlaylistHasMusic('search');
		$musicIn->unsetAttributes();
		$musicIn->playlist_id = $id;
		if(isset($_GET['PlaylistHasMusic']))
			$musicIn->attributes=$_GET['PlaylistHasMusic'];
		
		$this->render('view', array('model' => $model, 'musicNotIn' => $musicNotIn, 'musicIn' => $musicIn));
	}
	
	public function actionIndex(){
		$this->breadcrumbs=array(
				'Playlist',
		);
		
		$model=new Playlist('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Playlist']))
			$model->attributes=$_GET['Playlist'];
		
		$this->render('index',array(
				'model'=>$model,
		));
	}
	
	public function actionUpdate($id){
		$model = Playlist::model()->findByPk($id);
		
		$this->breadcrumbs=array(
				'Playlist' => array('index'),
				$model->name
		);
		
		if(isset($_POST['ajax']) && $_POST['ajax'] ==='Playlist-form'){
			echo CActiveForm::validate($formModel);
			Yii::app()->end();
		}
		
		if(isset($_POST['Playlist'])){
			$model->attributes=$_POST['Playlist'];
			// validate user input and redirect to the previous page if valid
			if($model->validate()){
				$model->attributes=$_POST['Playlist'];
				if($model->save()){
					$this->redirect(array('view', 'id' => $id));
				}
			}
		}
		
		$this->render('update', array('model' => $model));
	}
	
	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest)
		{
			Playlist::model()->findByPk($id)->delete();
			
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionAddMusic($id){
		$playlist = Playlist::model()->findByPk($id);
		$PlaylistHasMusic = PlaylistHasMusic::model()->findAllByAttributes(array('playlist_id' => $id));
		
		$id_not_in = array();
		
		foreach ($PlaylistHasMusic as $value){
			$id_not_in[] = $value->music_id;
		}
		
		$this->breadcrumbs=array(
				'Playlist' => array('index'),
				ucfirst($playlist->name) => array('view' => $id),
				'Add Music'
		);
		
		$music=new Music('findForPlaylist1');
		$music->unsetAttributes();  // clear any default values
		$music->id_not_in = $id_not_in;
		if(isset($_GET['Music']))
			$music->attributes=$_GET['Music'];
		
		
		$this->render('addmusic', array('playlist' => $playlist, 'music' => $music));
	}
	
	public function actionPlayNow($id){
		$playlist = Playlist::model()->with('PlaylistHasMusic', 'Music')->findByAttributes(array('playlist_id' => $id));
		
		$musics = $playlist->Music;

		$playlist_file = Yii::app()->params['playlist_file'];
		$nowplaying = Yii::app()->params['nowplaying'];
		
		$playlist_txt = '';
		
		foreach($musics as $music){
			$playlist_txt .= Yii::app()->params['music_dir'] . $music->filename . "\n";			
		}
		
		if (is_writable($playlist_file)) {

			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence
			// that's where $somecontent will go when we fwrite() it.
			if (!$handle = fopen($playlist_file, 'w')) {
				throw new CHttpException(500, "Cannot open file ($playlist_file)");
				exit;
			}

			// Write $somecontent to our opened file.
			if (fwrite($handle, $playlist_txt) === FALSE) {
				echo "Cannot write to file ($playlist_file)";
				throw new CHttpException(500, "Cannot write to file ($playlist_file)");
				exit;
			}

			fclose($handle);

		} else {
			throw new CHttpException(500, "The file $playlist_file is not writable");
		}
		
		if (is_writable($nowplaying)) {

			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence
			// that's where $somecontent will go when we fwrite() it.
			if (!$handle = fopen($nowplaying, 'w')) {
				throw new CHttpException(500, "Cannot open file ($nowplaying)");
				exit;
			}

			// Write $somecontent to our opened file.
			if (fwrite($handle, $id) === FALSE) {
				echo "Cannot write to file ($nowplaying)";
				throw new CHttpException(500, "Cannot write to file ($nowplaying)");
				exit;
			}

			fclose($handle);

		} else {
			throw new CHttpException(500, "The file $nowplaying is not writable");
		}
		
		$this->redirect(array('/nowplaying/index'));
		
	}
	
	public function actionAjaxAddMusic($id){
		// Get last queue
		$last_queue = Yii::app()->db->createCommand()
						->select('queue')
						->from(PlaylistHasMusic::model()->tableName())
						->where("playlist_id=:id", array(':id' => $id))
						->order("queue DESC")
						->queryScalar();
		var_dump($last_queue);
		if(is_null($last_queue) || !$last_queue){
			$last_queue = 0;
		}
		
		echo $last_queue;
		
		foreach ($_POST['music_id'] as $selected){
			$last_queue++;
			$PlaylistHasMusic = new PlaylistHasMusic;
			$PlaylistHasMusic->playlist_id = $id;
			$PlaylistHasMusic->music_id = $selected;
			$PlaylistHasMusic->queue = $last_queue;
			$PlaylistHasMusic->save();
		}
	}
	
	public function actionAjaxRemoveMusic(){
		foreach ($_POST['playlist_music_id'] as $selected){
			$sel = explode(',', trim($selected));
			
			$playlist_id = trim($sel[0]);
			$music_id = trim($sel[1]);
			
			PlaylistHasMusic::model()->findByAttributes(array('playlist_id' => $playlist_id, 'music_id' => $music_id))->delete();
		}
	}
	
}