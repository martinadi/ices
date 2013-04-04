<?php
class NowplayingController extends Controller{
	public function actionIndex(){
		
		$stat = file_get_contents("http://admin:martinadi@stream.hosting.ozip.co.id:8000/admin/stats.xml");
		
		Yii::import('ext.helper', true);
		
		$helper = new helper();
		
		$stat_array = $helper->xml2array($stat);
		
		$nowPlaying = explode(' - ', $stat_array['icestats']['source']['title']);
		$current_artist = $nowPlaying[0];
		$current_title = $nowPlaying[1];
		//exit;
		
		$playlist_id = file_get_contents(Yii::app()->params['nowplaying']);
		
		$musicIn=new PlaylistHasMusic('search');
		$musicIn->unsetAttributes();
		$musicIn->playlist_id = $playlist_id;
		if(isset($_GET['PlaylistHasMusic']))
			$musicIn->attributes=$_GET['PlaylistHasMusic'];
		
		$this->render('index', array('musicIn' => $musicIn, 'nowPlaying' => $nowPlaying));
	}
}