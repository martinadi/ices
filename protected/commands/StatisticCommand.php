<?php
class StatisticCommand extends CConsoleCommand{
	public function actionGetListener(){
		$listener_xml = file_get_contents(Yii::app()->params['listener_url']);

		Yii::import('ext.helper');

		$helper = new helper();

		$listener_array = $helper->xml2array($listener_xml);
		
		try {
			$listener_array = $listener_array['icestats'];
			if($listener_array['source']['Listeners']){
				//$listener_log = ListenerLog::model()->findByAttributes(array('ip' ))
				if($listener_array['source']['Listeners'] > 1){
					foreach($listener_array['source']['listener'] as $listener){
						print_r($listener['IP']);
						echo "\n";
						$listener_id 	= $listener['ID'];
						$ip 			= $listener['IP'];
						$user_agent 	= $listener['UserAgent'];
						$conected_time 	= $listener['Connected'];
						ListenerLog::model()->log($listener_id, $ip, $user_agent, $conected_time);
					}
				}else{
					echo $listener_array['source']['listener']['IP'];
					$listener_id = $listener_array['source']['listener']['ID'];
					$ip = $listener_array['source']['listener']['IP'];
					$user_agent = $listener_array['source']['listener']['UserAgent'];
					$conected_time = $listener_array['source']['listener']['Connected'];
					ListenerLog::model()->log($listener_id, $ip, $user_agent, $conected_time);
				}
				//print_r($listener_array['source']['listener']);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function actionGeoIp(){
		$location = Yii::app()->geoip->lookupLocation('202.137.21.226');
		var_dump($location->countryCode, 
				$location->countryCode3, 
				$location->countryName, 
				$location->region, 
				$location->regionName, 
				$location->city, 
				$location->postalCode,
  				$location->latitude,
  				$location->longitude,
  				$location->areaCode,
  				$location->dmaCode);		
	}
}