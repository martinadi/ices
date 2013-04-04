<?php
class StatisticController extends Controller{
	public function actionCurrent(){
		$listener_xml = file_get_contents(Yii::app()->params['listener_url']);
		Yii::import('ext.helper');
		$helper = new helper();
		$listener_raw = $helper->xml2array($listener_xml);
		$listener_raw = $listener_raw['icestats']; 
		
		$listeners = $listener_raw['source']['Listeners'];
		
		$listener_array = array();
		
		//print_r($listener_raw);exit;
		
		if($listeners > 1){
			foreach($listener_raw['source']['listener'] as $listener){
				$location = Yii::app()->geoip->lookupLocation($listener['IP']);
				
				$listener_array[] = array(
					'id' => $listener['ID'],
					'ip' => $listener['IP'],
					'user_agent' => $listener['UserAgent'],
					'conected_time' => $listener['Connected'],
						
					'countryName' => $location->countryName,
					'regionName' => $location->regionName,
					'city' => $location->city,
					'latitude' => $location->latitude,
					'longitude' => $location->longitude,
				);
			}
		}else if($listeners == 1){			
			$location = Yii::app()->geoip->lookupLocation($listener_raw['source']['listener']['IP']);
			$listener_array[] = array(
					'id' => $listener_raw['source']['listener']['ID'],
					'ip' => $listener_raw['source']['listener']['IP'],
					'user_agent' => $listener_raw['source']['listener']['UserAgent'],
					'conected_time' => $listener_raw['source']['listener']['Connected'],
					
					'countryName' => $location->countryName,
					'regionName' => $location->regionName,
					'city' => $location->city,
					'latitude' => $location->latitude,
					'longitude' => $location->longitude,
			);
		}else{
			$listener_array = array();
		}
		
		//print_r($listener_array);exit;
		
		$gridDataProvider = new CArrayDataProvider($listener_array);
		
		$this->render('current', array('gridDataProvider' => $gridDataProvider));
	}
	
	public function actionJsonGetStat(){
		$stat_xml = file_get_contents(Yii::app()->params['stat_url']);
		if($stat_xml !== false){
			Yii::import('ext.helper');
			$helper = new helper();
			
			$stat = $helper->xml2array($stat_xml);
			$return = array('status' => true, 'data' => $stat['icestats']); 
		}else{
			$return = array('status' => false);
		}
		header('Content-Type: application/json');
		echo CJSON::encode($return);
	}
	public function actionJsonGetListener(){
		$listener_xml = file_get_contents(Yii::app()->params['listener_url']);
		
		if($listener_xml !== false){
			Yii::import('ext.helper');
			$helper = new helper();
			
			$listener = $helper->xml2array($listener_xml);
			$return = array('status' => true, 'data' => $listener);
		}else{
			$return = array('status' => false);
		}
		header('Content-Type: application/json');
		echo CJSON::encode($return);
	}
	
}