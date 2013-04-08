<?php
class ScheduleCommand extends CConsoleCommand{
	public function actionPlayNow(){
		$now = time();

		$start_a = $now - 300;
		$start_b = $now + 300;

		$playlist_id = Yii::app()->db->createCommand()
		->select('playlist_id')
		->from('schedule')
		->where('start between :start_a AND :start_b', array('start_a' => $start_a, 'start_b' => $start_b))
		->queryScalar();	

		var_dump($playlist_id);
		
		if($playlist_id){
			$playlist = Playlist::model()->with('PlaylistHasMusic', 'Music')->findByAttributes(array('playlist_id' => $playlist_id));
				
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
				if (fwrite($handle, $playlist_id) === FALSE) {
					echo "Cannot write to file ($nowplaying)";
					throw new CHttpException(500, "Cannot write to file ($nowplaying)");
					exit;
				}
					
				fclose($handle);
					
			} else {
				throw new CHttpException(500, "The file $nowplaying is not writable");
			}
		}


	}
}